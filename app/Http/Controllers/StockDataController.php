<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StockDataController extends Controller
{
    /**
     * Get stock data from Yahoo Finance
     */
    public function getStockData(Request $request)
    {
        $symbol = strtoupper((string) $request->query('symbol', env('STOCK_SYMBOL', 'SPINNEYS.AE')));
        $allowedSymbols = [
            'SPINNEYS.AE',
            'AYALY',
        ];

        if (!in_array($symbol, $allowedSymbols, true)) {
            return response()->json([
                'error' => 'Unsupported symbol.'
            ], 422);
        }

        // Cache key for the stock data
        $cacheKey = 'stock_data_' . $symbol . '_' . now()->format('Y-m-d-H-i');

        // Cache for 5 minutes to match update frequency
        $data = Cache::remember($cacheKey, 300, function () use ($symbol) {
            return $this->fetchFromYahooFinance($symbol);
        });

        return response()->json($data);
    }

    /**
     * Generate realistic simulated stock data with random walk
     * Mimics real market behavior with volatility, trends, and noise
     */
    private function generateStockData()
    {
        $basePrice = (float) env('STOCK_BASE_PRICE', 1.55); // Starting price for Spinneys stock
        $volatility = 0.003; // Lower volatility for a low-priced stock

        // Generate candlestick data for the last 24 hours (5-minute intervals)
        $candlesticks = [];
        $now = now();
        $intervals = 288; // 24 hours * 60 minutes / 5 minutes

        $lastClose = $basePrice;

        for ($i = $intervals; $i >= 0; $i--) {
            $timestamp = $now->copy()->subMinutes($i * 5)->timestamp * 1000;

            // Random walk with momentum
            $randomChange = (mt_rand(-100, 100) / 100) * $volatility * $lastClose;
            $momentum = (mt_rand(-50, 50) / 1000) * $lastClose; // Add momentum

            $open = $lastClose;
            $close = $open + $randomChange + $momentum;

            // Generate high and low within the candle
            $maxMove = abs($close - $open) * (mt_rand(120, 200) / 100);
            $high = max($open, $close) + (mt_rand(0, 100) / 100) * $maxMove;
            $low = min($open, $close) - (mt_rand(0, 100) / 100) * $maxMove;

            // Add occasional spikes/wicks (real market behavior)
            if (mt_rand(1, 20) === 1) {
                $high += (mt_rand(50, 150) / 100) * $volatility * $lastClose;
            }
            if (mt_rand(1, 20) === 1) {
                $low -= (mt_rand(50, 150) / 100) * $volatility * $lastClose;
            }

            // Ensure high is highest and low is lowest
            $high = max($high, $open, $close);
            $low = min($low, $open, $close);

            // Prevent negative prices
            $low = max($low, $basePrice * 0.5);

            $candlesticks[] = [
                'x' => $timestamp,
                'y' => [
                    round($open, 2),
                    round($high, 2),
                    round($low, 2),
                    round($close, 2)
                ]
            ];

            $lastClose = $close;
        }

        // Calculate current price and change
        $currentPrice = $lastClose;
        $openPrice = $candlesticks[0]['y'][0];
        $change = $currentPrice - $openPrice;
        $changePercent = ($change / $openPrice) * 100;

        $series = array_map(function ($candle) {
            return [
                'x' => $candle['x'],
                'y' => $candle['y'][3],
            ];
        }, $candlesticks);

        return [
            'symbol' => strtoupper(env('STOCK_SYMBOL', 'SPINNEYS')),
            'currentPrice' => round($currentPrice, 2),
            'change' => round($change, 2),
            'changePercent' => round($changePercent, 2),
            'candlesticks' => $candlesticks,
            'series' => $series,
            'lastUpdate' => now()->toIso8601String(),
            'source' => 'Simulated (Spinneys base price)'
        ];
    }

    /**
     * Fetch real stock data from Alpha Vantage API
     */
    private function fetchFromAlphaVantage()
    {
        $apiKey = env('ALPHAVANTAGE_API_KEY');
        $symbol = env('STOCK_SYMBOL', 'SPINNEYS'); // Default to Spinneys

        try {
            $url = "https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol={$symbol}&interval=5min&apikey={$apiKey}";

            $response = Http::timeout(10)->get($url);
            $data = $response->json();

            // Check for API errors
            if (isset($data['Error Message'])) {
                Log::warning('Alpha Vantage API Error', ['error' => $data['Error Message']]);
                return $this->generateStockData();
            }

            if (isset($data['Note'])) {
                Log::warning('Alpha Vantage API Rate Limit', ['note' => $data['Note']]);
                return $this->generateStockData();
            }

            // Transform API response to candlestick format
            $candlesticks = [];
            $timeSeriesKey = 'Time Series (5min)';

            if (isset($data[$timeSeriesKey])) {
                $timeSeries = $data[$timeSeriesKey];

                foreach ($timeSeries as $time => $values) {
                    $candlesticks[] = [
                        'x' => strtotime($time) * 1000,
                        'y' => [
                            floatval($values['1. open']),
                            floatval($values['2. high']),
                            floatval($values['3. low']),
                            floatval($values['4. close'])
                        ]
                    ];
                }

                // Reverse to get chronological order
                $candlesticks = array_reverse($candlesticks);

                // Calculate current price and change
                $latestCandle = end($candlesticks);
                $firstCandle = reset($candlesticks);

                $currentPrice = $latestCandle['y'][3]; // Close price
                $openPrice = $firstCandle['y'][0];
                $change = $currentPrice - $openPrice;
                $changePercent = ($change / $openPrice) * 100;

                return [
                    'symbol' => strtoupper($symbol),
                    'currentPrice' => round($currentPrice, 2),
                    'change' => round($change, 2),
                    'changePercent' => round($changePercent, 2),
                    'candlesticks' => $candlesticks,
                    'lastUpdate' => now()->toIso8601String(),
                    'source' => 'Alpha Vantage'
                ];
            }

            // Fallback to simulated data if no time series data
            Log::info('No time series data from Alpha Vantage, using simulated data');
            return $this->generateStockData();

        } catch (\Exception $e) {
            Log::error('Error fetching stock data from Alpha Vantage', [
                'error' => $e->getMessage()
            ]);

            // Fallback to simulated data on error
            return $this->generateStockData();
        }
    }

    /**
     * Fetch stock data from Yahoo Finance chart endpoint
     */
    private function fetchFromYahooFinance(string $symbol)
    {
        try {
            $response = Http::timeout(10)->get(
                "https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}",
                [
                    'range' => '6mo',
                    'interval' => '1d',
                    'includePrePost' => 'false',
                    'events' => 'div,splits',
                ]
            );

            $data = $response->json();
            $result = $data['chart']['result'][0] ?? null;

            if (!$result || empty($result['timestamp']) || empty($result['indicators']['quote'][0]['close'])) {
                Log::warning('Yahoo Finance returned no data', ['symbol' => $symbol]);
                return $this->generateStockData();
            }

            $timestamps = $result['timestamp'];
            $closes = $result['indicators']['quote'][0]['close'];

            $series = [];
            $firstClose = null;
            $lastClose = null;

            foreach ($timestamps as $index => $timestamp) {
                $close = $closes[$index] ?? null;
                if ($close === null) {
                    continue;
                }

                if ($firstClose === null) {
                    $firstClose = $close;
                }
                $lastClose = $close;

                $series[] = [
                    'x' => $timestamp * 1000,
                    'y' => round((float) $close, 4),
                ];
            }

            if ($firstClose === null || $lastClose === null) {
                return $this->generateStockData();
            }

            $change = $lastClose - $firstClose;
            $changePercent = $firstClose != 0 ? ($change / $firstClose) * 100 : 0;

            return [
                'symbol' => $symbol,
                'currentPrice' => round((float) $lastClose, 4),
                'change' => round((float) $change, 4),
                'changePercent' => round((float) $changePercent, 2),
                'series' => $series,
                'lastUpdate' => now()->toIso8601String(),
                'source' => 'Yahoo Finance',
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching stock data from Yahoo Finance', [
                'symbol' => $symbol,
                'error' => $e->getMessage(),
            ]);

            return $this->generateStockData();
        }
    }
}
