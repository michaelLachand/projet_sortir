<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\ImportType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends Controller
{
    /**
     * @Route("/import", name="import")
     */
    public function importAction(Request $request)
    {
        $formImport = $this->createForm(ImportType::class);
        $formImport->handleRequest($request);
        $utilisateurs = array(); // Tableau qui va contenir les éléments extraits du fichier CSV
        $row = 0; // Représente la ligne
         // Import du fichier CSV
        if ($formImport->isSubmitted() && $formImport->isValid()) {

            $file = $formImport['fichier_csv']->getData();

            if($file!= null){

                $fileName = 'utilisateur.csv';
                $file->move($this->getParameter('csv_directory'),$fileName);
                dump($file);
                $utilisateurs = array(); // Tableau qui va contenir les éléments extraits du fichier CSV
                $row = 0; // Représente la ligne

                dump($fileName);
            if (($handle = fopen( '../var/csv/'.$fileName, "r")) !== FALSE) { // Lecture du fichier, à adapter
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { // Eléments séparés par un point-virgule, à modifier si necessaire
                    $num = count($data); // Nombre d'éléments sur la ligne traitée
                    $row++;
                    for ($c = 0; $c < $num; $c++) {
                        $utilisateurs[$row] = array(
                            "site_id" => $data [0],
                            "login" => $data [1],
                            "nom" => $data[2],
                            "prenom" => $data[3],
                            "telephone" => $data[4],
                            "mail" => $data[5],
                            "password" => $data[6],
                            "administrateur" => $data[7],
                            "actif" => $data[8],
                        );
                    }
                }
                fclose($handle);

            }}


            $em = $this->getDoctrine()->getManager(); // EntityManager pour la base de données

            // Lecture du tableau contenant les utilisateurs et ajout dans la base de données
            foreach ($utilisateurs as $utilisateur) {

                // On crée un objet utilisateur
                $participants = new Participants();

                // Encode le mot de passe
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($participants);
                $plainpassword = $utilisateur["password"];
                $password = $encoder->encodePassword($plainpassword, $participants->getSalt());

                // Hydrate l'objet avec les informations provenants du fichier CSV
                $participants->setPassword($password);
                $participants->setNom($utilisateur["nom"]);
                $participants->setPrenom($utilisateur["prenom"]);
                $participants->setMail($utilisateur["mail"]);
                $participants->setLogin($utilisateur["login"]);
                $participants->setTelephone($utilisateur["telephone"]);
                $participants->setAdministrateur($utilisateur["administrateur"]);
                $participants->setActif($utilisateur["actif"]);


                // Enregistrement de l'objet en vu de son écriture dans la base de données
                $em->persist($participants);

            }

            // Ecriture dans la base de données
            $em->flush();
            return $this->redirectToRoute('accueil');
        }

        // Renvoi la réponse (ici affiche un simple OK pour l'exemple)
         return $this->render('import/index.html.twig', [
            'controller_name' => 'ImportController',
             'formImport' => $formImport->createView(),
        ]);
    }
}
