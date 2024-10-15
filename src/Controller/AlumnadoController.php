<?php

namespace App\Controller;

use App\Entity\Alumnado;
use App\Repository\AlumnadoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AlumnadoController extends AbstractController
{
    #[Route('/colegio', name: 'app_colegio')]
    public function index(AlumnadoRepository $alumandoRepository): Response
    {
        //ambas funcionan
        $parametros = array('titulo'=>'Dam2 Symfony');
        //$parametros['titulo']= 'Dam2 Symfony';

        $datos = $alumandoRepository->findAll();

        $parametros['alumnado'] = $datos;

        //dd($parametros);

        return $this->render('alumnado/index.html.twig', $parametros);
    }

    #[Route('/colegio/anadir', name: 'anadir')]
    public function anadir(AlumnadoRepository $alumnadoRepository)
    {
        $parametros = array('titulo'=>'Añadir alumnos', 'mensaje'=>null);

        $parametros['provincias']= array('Bizkaia', 'Gipuzkoa', 'Araba');

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {

            if($_POST['nombre'] == "")
            {

                $parametros['mensaje'] == 'Error falta rellenar el nombre';
            }else
            {
                $alumno = new Alumnado($_POST['dni'], $_POST['nombre'], $_POST['apellido1'],
                    $_POST['apellido2'],
                    \DateTime::createFromFormat('Y-m-d', $_POST['fecha']),
                    $_POST['provincia']);

                $alumnadoRepository->add($alumno);

                $parametros['mensaje'] == 'Alumno insertado';

                return $this->redirectToRoute('app_colegio');
            }
        }
        return $this->render('alumnado/anadir.html.twig', $parametros);
    }

    #[Route('/colegio/borrar/{id}', name: 'borrar')]
    public function eliminar($id, AlumnadoRepository $alumnadoRepository)
    {
        $alumno = $alumnadoRepository->findOneBy(['id'=>$id]);

        $alumnadoRepository->remove($alumno);

        return $this->redirectToRoute('app_colegio');
    }


    #[Route('/colegio/editar/{id}', name: 'editar')]
    public function editar($id, AlumnadoRepository $alumnadoRepository)
    {
        $alumno = $alumnadoRepository->findOneBy(['id' => $id]);
        $parametros = array('titulo' => 'Editar alumno', 'mensaje' => null, 'alumno' => $alumno);

        $parametros['provincias'] = array('Bizkaia', 'Gipuzkoa', 'Araba');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['nombre'] == "") {
                $parametros['mensaje'] = 'Error falta rellenar el nombre';
            } else {
                $alumno->setDni($_POST['dni']);
                $alumno->setNombre($_POST['nombre']);
                $alumno->setApellido1($_POST['apellido1']);
                $alumno->setApellido2($_POST['apellido2']);
                $alumno->setFecha(\DateTime::createFromFormat('Y-m-d', $_POST['fecha']));
                $alumno->setProvincia($_POST['provincia']);

                $alumnadoRepository->update($alumno);

                $parametros['mensaje'] = 'Alumno actualizado';

                return $this->redirectToRoute('app_colegio');
            }
        }

        return $this->render('alumnado/editar.html.twig', $parametros);
    }

}
