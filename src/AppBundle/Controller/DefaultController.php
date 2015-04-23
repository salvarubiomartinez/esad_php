<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:default:index.html.twig');
    }

    /**
     * @Route("/notre_mission", name="notre_mission")
     */
    public function missionAction()
    {
        return $this->render('AppBundle:default:notre_mission.html.twig');
    }

    /**
     * @Route("/actions/{any}", defaults={"any" = 2015},name="actions")
     */
    public function actionsAction($any)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Action');
        $actions=$repository->findAll(array(),array('date'=> 'DESC'));

//        $classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', 'DoctrineExtensions\src\Query\Mysql\Year');
//        $classLoader->register();
////
//        $emConfig = $this->getEntityManager()->getConfiguration();
//        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

        $query = $repository->createQueryBuilder('actions')
            ->select('DISTINCT YEAR(actions.date)')
//            ->Where('actions.date = :date')
            ->orderBy('actions.date')
            ->getQuery();

        $year=$query->getResult();


        return $this->render('AppBundle:default:actions.html.twig',array('any'=> $any,'actions'=>$actions,'year'=>$year));
    }

    /**
     * @Route("/aider", name="aider")
     */
    public function aiderAction()
    {
        return $this->render('AppBundle:default:aider.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
        return $this->render('AppBundle:default:contact.html.twig');
    }

    /**
     * @Route("/notre_mission/education",name="education")
     */
    public function educationAction()
    {
        return $this->render('AppBundle:default:education.html.twig');
    }

    /**
     * @Route("/notre_mission/sante",name="sante")
     */
    public function santeAction()
    {
        return $this->render('AppBundle:default:sante.html.twig');
    }

    /**
     * @Route("/notre_mission/developpement",name="developpement")
     */
    public function developpementAction()
    {
        return $this->render('AppBundle:default:developpement.html.twig');
    }

    /**
     * @Route("/Envoyer", name="envoyer")
     */
    public function sendAction(Request $request)
    {
        $nom=$request->request->get('nom');
        $email=$request->request->get('e-mail');
        $site=$request->request->get('site');
        $textarea=$request->request->get('textarea');

        $mailer = $this->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject('esad contact')
            ->setFrom('contact@esad.fr')
            ->setTo('esad.sarcelles@gmail.com')
            ->setBody('nom: '.$nom.'<br/>e-mail: '.$email.'<br/> Site: '.$site.'<br/> Message: '.$textarea,'text/html')
        ;
        $mailer->send($message);

        return $this->render('AppBundle:default:envoyer.html.twig');
    }
}
