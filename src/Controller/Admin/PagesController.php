<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{

    /**
     * @Route("/admin/pages/rules", name="admin.pages.rules")
     * @Route("/admin/pages/cookies", name="admin.pages.cookies")
     * @param Request $request
     */
    public function index(Request $request)
    {
        if($request->attributes->get('_route') == "admin.pages.cookies") {
            $template= 'C:\wamp64\www\testforum\templates\pages\cookies.html.twig';
            $template_nom = 'Utilisation des cookies';
        }
        else {
            $template= 'C:\wamp64\www\testforum\templates\pages\rules.html.twig';
            $template_nom = 'Réglement';
        }
        $fichier_a_ouvrir = fopen($template, "r");
		$fichier ='';
		while(!feof($fichier_a_ouvrir))
		{
			$fichier .= fgets($fichier_a_ouvrir);
		}
        fclose ($fichier_a_ouvrir);
        if('POST' === $request->getMethod() && $request->request->get('tMessage') != null) {
            $contenu = $request->request->get('tMessage');
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

            if($request->attributes->get('_route') == "admin.pages.cookies") {
                return $this->redirectToRoute('admin.pages.cookies');
            }
            else {
                return $this->redirectToRoute('admin.pages.rules');
            }

        }
        return $this->render('admin/pages/index.html.twig', [
            'fichier' => $fichier,
            'nom' => $template_nom,
        ]);
    }
}
