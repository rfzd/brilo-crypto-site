import React, { useState, useEffect } from 'react';

const PriceComponent = ({btcPricesUrl}) => {
    const [coinPrices, setCoinPrices] = useState([]);

    const fetchCoinPrices = async () => {
        try {
            const response = await fetch(btcPricesUrl);

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            if (data.prices === undefined || data.prices === null) {
                throw new Error('Coin prices was not fetched.');
            }

            setCoinPrices(data.prices);
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    };

    useEffect(() => {
        fetchCoinPrices();
        const intervalId = setInterval(fetchCoinPrices, 5000);

        return () => {
            clearInterval(intervalId);
        };
    }, []);

    return (
        <div className="relative flex min-h-screen flex-col justify-center overflow-hidden bg-gray-50 py-6 sm:py-12">
            <div className="absolute inset-0 bg-center"></div>
            <div
                className="relative bg-white px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5 sm:mx-auto sm:max-w-lg sm:rounded-lg sm:px-10">
                <div className="mx-auto max-w-md">
                    <div className="divide-y divide-gray-300/50">
                        <div className="space-y-6 py-8 text-base leading-7 text-gray-600">
                            <p className="text-base font-semibold">1 BTC</p>
                            <ul className="space-y-2">
                                {coinPrices.map((coinPrice, index) => (
                                    <li key={index} className="flex items-center">
                                        <p className="ml-4">
                                            = {coinPrice.rate} {coinPrice.code}
                                        </p>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default PriceComponent;
