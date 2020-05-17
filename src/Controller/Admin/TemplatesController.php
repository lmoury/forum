<?php

namespace App\Controller\Admin;

use App\Repository\ConfigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TemplatesController extends AbstractController
{

    /**
     * @Route("/admin/templates", name="admin.templates")
     * @param Request $request
     */
    public function index(Request $request, ConfigRepository $repository)
    {
        $urlAbsolute = $repository->getUrlAbsolute();
        return $this->render('admin/templates/index.html.twig', [
            'urlAbsolute' => $urlAbsolute
        ]);
    }



    /**
    * @Route("/admin/templates/fichier", name="admin.templates.fichier")
     * @param Request $request
     */
    public function pages(Request $request)
    {
        $template= $request->query->get('url');

        $fichier_a_ouvrir = fopen($template, "r");
		$fichier ='';
		while(!feof($fichier_a_ouvrir))
		{
			$fichier .= fgets($fichier_a_ouvrir);
		}
        fclose ($fichier_a_ouvrir);
        if('GET' === $request->getMethod() && $request->query->get('tMessage') != null) {
            $contenu = $request->query->get('tMessage');
            $filename = $template;
            if (is_writable($filename)) { // Le fichier est il inscriptible
            	if (!$handle = fopen($filename, 'w+')) { // Je vous conseil de lire la fonction fopen($filename, 'a')
            		$this->addFlash('success', "Impossible d'ouvrir le fichier ($filename)");
            	}
            	if (fwrite($handle, $contenu) === FALSE) { // On écrit dans le fichier en testant les droits
            		$this->addFlash('success',  "Le fichier $filename n'est pas inscriptible");
            	}
                $this->addFlash('success',  "L'écriture dans le fichier ($filename) a réussi");
            	fclose($handle); // on ferme le fichier aprés avoir écrit dedans
            }
            else {
                $this->addFlash('success',  "Le fichier $filename n'est pas accessible en écriture.");
            }
            return $this->redirectToRoute('admin.templates');

        }
        return $this->render('admin/templates/_form.html.twig', [
            'fichier' => $fichier
        ]);
    }
}
