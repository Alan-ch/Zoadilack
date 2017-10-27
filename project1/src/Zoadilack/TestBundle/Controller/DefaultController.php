<?php

namespace Zoadilack\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Bundle\SwiftmailerBundle\Swiftmailer;



class DefaultController extends Controller
{
    /**
     * Get databases configuration
     *
     * @return array
     */
     public function conf(){
       $x = [];
       $x['database1'][] = $this->container->getParameter('database_driver');
       $x['database1'][] = $this->container->getParameter('database_host');

       $x['database1'][] = $this->container->getParameter('database_port');

       $x['database1'][] = $this->container->getParameter('database_name');

       $x['database1'][] = $this->container->getParameter('database_user');
       $x['database1'][] = $this->container->getParameter('database_password');

       $x['database2'][] = $this->container->getParameter('database_driver2');
       $x['database2'][] = $this->container->getParameter('database_host2');

       $x['database2'][] = $this->container->getParameter('database_port2');

       $x['database2'][] = $this->container->getParameter('database_name2');

       $x['database2'][] = $this->container->getParameter('database_user2');
       $x['database2'][] = $this->container->getParameter('database_password2');

          return $x;

     }
    /**
     * @Route("/Zoadilack",name="Zoadilack")
     * @Template()
     * @Method("GET")
     */
    public function indexAction()
    {
      $x = $this->conf();
        return array('conf' => $x);
    }
    /**
     * @Route("/Zoadilack/notify", name="notify")
     *
     * @Method("POST")
     */
    public function notifyAction(Request $request)
    {
      $x = $this->conf();
      $email = $request->get('email');
      if($email == ''){
      return $this->redirect($this->generateUrl('Zoadilack',array('error' => 'Please enter your email' )));
      }
       $emailConstraint = new EmailConstraint();
       $msg = 'Please enter a valid email';

      $errors = $this->get('validator')->validate(
        $email,
        $emailConstraint
      );

      if($errors == ''){
        $body = $this->container->get('templating')->render('ZoadilackTestBundle:Default:mail.html.twig', array('conf' => $x));

        # Setup the message
          $message = \Swift_Message::newInstance()
             ->setSubject('Some Subject')
             ->setFrom('Zoadilack@gmail.com')
             ->setTo($email)
             ->setBody($body, 'text/html');

          # Send the message
          $this->get('mailer')
             ->send($message);
      return $this->redirect($this->generateUrl('Zoadilack',array('error' => 'Your email has been sent' )));

      }else{
        return $this->redirect($this->generateUrl('Zoadilack',array('error' => $msg )));

      }
    }

}
