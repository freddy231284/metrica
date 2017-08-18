<?php
/**
 * Report UI Controller
 */

namespace App\Controller;

use Silex\Application;
use Silex\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class ReportUiController extends Controller
{
    private $monolog;
    private $path_json;
    private $app;

    /**
     * ReportUiController constructor.
     * @param Application $app
     * @param string $channel
     */
    public function __construct(Application $app, string $channel)
    {
        //Define the Monolog Channel
        $this->monolog = $app['monolog.' . $channel];

        $this->path_json = __DIR__ . '/../../resources/json/';
        $this->app = $app;
    }

    /**
     * Index Report UI
     * @param Application $app
     * @param Request $request
     * @return Response
     */
    public function index(Application $app, Request $request)
    {
        return new Response('Report UI');
    }

    /**
     * Index Report UI for generate list employees
     * @param Application $app
     * @param Request $request
     * @return Response
     */
    public function generate(Application $app, Request $request)
    {
        $form = $app['form.factory']->createBuilder(FormType::class, [])
            ->add('type', TextType::class, array(
                'attr' => [
                    'class' => '',
                    'placeholder' => 'Buscar',
                ],
            ))
            ->add('generate', SubmitType::class, [
                'label' => 'Generar',
                'attr' => array('class' => 'btn btn-primary'),
            ])
            ->getForm();

        $form->handleRequest($request);

        $content = json_decode(file_get_contents($this->path_json . "/employees.json"), true);
        if ($request->isMethod('POST')) {

            if ($form->isValid()) {
                $data = $form->getData();
                $input = $data['type'];

                if ($form->get('generate')->isClicked()) {

                    $filterBy = $input;

                    $data_filter = array_filter($content, function ($var) use ($filterBy) {
                        return ($var['email'] == $filterBy);
                    });
                }
            }
        } else {
            $data_filter = $content;
        }

        // display the form
        return $app['twig']->render('report/report.generate.html.twig', array(
                'form' => $form->createView(),
                'employees' => $data_filter
            )
        );

    }

    /**
     * Show details of employee
     * @param Application $app
     * @param $id
     * @return mixed
     */
    public function details(Application $app, $id)
    {
        $content = json_decode(file_get_contents($this->path_json . "/employees.json"), true);
        $filterBy = $id;
        $data_filter = array_filter($content, function ($var) use ($filterBy) {
            return ($var['id'] == $filterBy);
        });
        // display the form
        return $app['twig']->render('report/report.details.html.twig', array(
            'data' => $data_filter[key($data_filter)]
        ));

    }

}