<?php

namespace App\Helper;

use Illuminate\Http\UploadedFile;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class csvHelper {
    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    public static function getCsvDetail(UploadedFile $csvFile, array $matchedHeader = []): array
    {
        $csv = Reader::createFromPath($csvFile->getRealPath(), 'r');
        $csv->setHeaderOffset(0);
        $csvJson = json_decode(json_encode($csv), true);

        $header = $csv->getHeader();
        if ($matchedHeader) {
            if (count(array_diff($matchedHeader, $header)) > 0) {
                return [
                    'status' => 'error',
                    'data' => [],
                    'message' => 'Some required headers are not found',
                ];
            }

            foreach ($csvJson as $key => $value) {
                foreach (array_diff($header, $matchedHeader) as $k => $v) {
                    unset($csvJson[$key][$v]);
                }
            }
        }

        return [
            'status' => 'success',
            'data' => [
                'rows' => count($csvJson),
                'data' => $csvJson,
                'header' => $header
            ],
            'message' => 'CSV has parsed successfully',
        ];
    }
}
