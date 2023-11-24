<?php

namespace Newageerp\SfBins\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfBins\Service\BinService;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfFiles\Service\FilesHelperService;
use Newageerp\SfSocket\Service\SocketService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(path="/app/nae-core/bin")
 */
class BinController extends OaBaseController
{
    protected string $storageDir = '';

    protected string $remoteUrl = 'http://artifactory.767.lt:8000/index.php';

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, SocketService $socketService)
    {
        parent::__construct($em, $eventDispatcher, $socketService);

        $userStorage = LocalConfigUtilsV3::getUserStoragePath() . '/bin';
        if (!is_dir($userStorage)) {
            mkdir($userStorage);
        }

        $this->storageDir = $userStorage;
    }

    /**
     * @Route ("/download", methods={"GET"})
     */
    public function download()
    {
        $filenameOrg = filter_input(INPUT_GET, 'f');
        [$group, $package] = explode("/", $filenameOrg);
        $filename = $group . '_' . $package;

        $userStorage = $this->storageDir;

        $tmpFile = sys_get_temp_dir() . '/' . $filename . '.zip';
        $zipContents = file_get_contents($this->remoteUrl . '?action=download&f=' . $filenameOrg);
        file_put_contents($tmpFile, $zipContents);

        if (!is_dir($userStorage . '/' . $group)) {
            mkdir($userStorage . '/' . $group);
        }
        if (!is_dir($userStorage . '/' . $group . '/' . $package)) {
            mkdir($userStorage . '/' . $group . '/' . $package);
        }

        $zip = new \ZipArchive();
        $res = $zip->open($tmpFile);
        if ($res === TRUE) {
            $zip->extractTo($userStorage . '/' . $group . '/' . $package);
            $zip->close();

            shell_exec('chmod a+x ' . $userStorage . '/' . $group . '/' . $package . '/cli-release');
        } else {
            return $this->redirect('/app/nae-core/bin/list?success=false');
        }

        return $this->redirect('/app/nae-core/bin/list?success=true');
    }

    /**
     * @Route ("/list", methods={"GET"})
     */
    public function list()
    {
        $userStorage = $this->storageDir;

        $downloaded = [];
        $groups = FilesHelperService::scanDirFolders($userStorage);
        foreach ($groups as $group) {
            $packages = FilesHelperService::scanDirFolders($userStorage . '/' . $group);

            foreach ($packages as $package) {
                $downloaded[] = [
                    'group' => $group,
                    'name' => $package,
                    'files' => FilesHelperService::scanDirFiles($userStorage . '/' . $group . '/' . $package),
                    'executables' => array_filter(
                        FilesHelperService::scanDirFiles($userStorage . '/' . $group . '/' . $package),
                        function (string $file) use ($userStorage, $group, $package) {
                            return is_executable($userStorage . '/' . $group . '/' . $package . '/' . $file);
                        }
                    )
                ];
            }
        }

        $list = json_decode(file_get_contents($this->remoteUrl . '?action=list'), true);

        return $this->render('bin_list.html.twig', ['list' => $list, 'downloaded' => $downloaded]);
    }

    /**
     * @Route ("/file", methods={"GET", "POST"})
     */
    public function fileUpdate()
    {
        $userStorage = $this->storageDir;

        $fileName = filter_input(INPUT_GET, 'f');

        if (!file_exists($userStorage . '/' . $fileName)) {
            file_put_contents($userStorage . '/' . $fileName, '');
        }

        return $this->render('bin_file.html.twig', ['fileName' => $fileName, 'fileContents' => file_get_contents($userStorage . '/' . $fileName)]);
    }

    /**
     * @Route ("/file-save", methods={"POST"})
     */
    public function fileSave(Request $request)
    {
        $userStorage = $this->storageDir;

        $fileName = $request->get('file');
        $content = $request->get('content');

        file_put_contents($userStorage . '/' . $fileName, $content);

        return $this->redirect('/app/nae-core/bin/list');
    }

    /**
     * @Route ("/run", methods={"GET", "POST"})
     */
    public function run(BinService $binService, Request $request)
    {
        $output = $binService->execute($request->get('group'), $request->get('package'), explode(" ", $request->get('params')));

        return $this->json(['output' => $output]);
    }
}
