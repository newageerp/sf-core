<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Newageerp\SfXlsx\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfUservice\Controller\UControllerBase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Newageerp\SfSocket\Service\SocketService;

/**
 * @Route(path="/app/nae-core/xlsx")
 */
class XlsxController extends UControllerBase
{
    protected array $letters = [];

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, SocketService $socketService)
    {
        parent::__construct($em, $eventDispatcher, $socketService);

        $this->letters = ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z'];
    }

    /**
     * @Route ("/toArray", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @OA\Post (operationId="NAEXlsxToArray")
     * @throws Exception
     */
    public function toArray(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $request = $this->transformJsonBody($request);

        $user = $this->findUser($request);
        if (!$user) {
            throw new Exception('Invalid user');
        }
        AuthService::getInstance()->setUser($user);

        $files = $request->files;

        $data = [];

        /**
         * @var UploadedFile $file
         */
        foreach ($files as $file) {
            $spreadsheet = IOFactory::load($file->getPathname());

            foreach ($spreadsheet->getAllSheets() as $sheet) {
                $sheetData = $sheet->toArray();
                $data[$sheet->getTitle()] = $sheetData;
            }
        }

        return $this->json(['success' => 1, 'data' => $data]);
    }
}