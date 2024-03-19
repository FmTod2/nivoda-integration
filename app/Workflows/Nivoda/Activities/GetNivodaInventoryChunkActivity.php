<?php

namespace App\Workflows\Nivoda\Activities;

use App\Http\Integrations\Nivoda\NivodaConnector;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Workflow\Activity;

class GetNivodaInventoryChunkActivity extends Activity
{
    /**
     * @var int
     */
    public $tries = 1;

    public function execute(NivodaConnector $connector, string $auth, int $offset, int $limit, ?string $query = null): array
    {
        $authenticator = AccessTokenAuthenticator::unserialize($auth);
        $connector->authenticate($authenticator);

        $response = $connector->diamond()->all([
            'items' => [
                'id',
                'price',
                'discount',
                'markup_price',
                'markup_discount',
                'diamond' => [
                    'id',
                    'v360' => [
                        'url',
                        'renumbered',
                        'frame_count',
                        'top_index',
                        'dl_link',
                    ],
                    'eyeClean',
                    'video',
                    'image',
                    'milky',
                    'certificate' => [
                        'id',
                        'v360' => [
                            'url',
                            'renumbered',
                            'frame_count',
                            'top_index',
                        ],
                        'image',
                        'video',
                        'lab',
                        'certNumber',
                        'diamonds' => [
                            'id'
                        ],
                        'shape',
                        'fullShape',
                        'carats',
                        'clarity',
                        'cut',
                        'polish',
                        'symmetry',
                        'color',
                        'colorShade',
                        'f_color',
                        'f_overtone',
                        'f_intensity',
                        'length',
                        'width',
                        'depth',
                        'comments',
                        'table',
                        'depthPercentage',
                        'girdle',
                        'culetSize',
                        'pdfUrl',
                        'floInt',
                        'floCol',
                        'labgrown',
                        'labgrown_type',
                        'treated',
                    ],
                    'CertificateType',
                    'return_window',
                    'availability',
                    'delivery_time' => [
                        'express_timeline_applicable',
                        'min_business_days',
                        'max_business_days',
                    ],
                    'supplierStockId',
                ],
            ],
        ], $query, offset: $offset, limit: $limit)->throw();

        return $response->json('data.diamonds_by_query.items', []);
    }
}
