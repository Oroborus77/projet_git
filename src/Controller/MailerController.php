<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/newsletter", name="app_newsletter")
     */
    public function sendEmail(MailerInterface $mailer): Response
    {
		/*  EXEMPLE
        $email = (new Email())
            ->from(Address::create('NeoflipsWizards <neoflipswizards@gmail.com>'))
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
			    ->getHeaders()
        // this non-standard header tells compliant autoresponders ("email holiday mode") to not
        // reply to this message because it's an automated email
        ->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply')
*/

/*  ENVOI EMAIL SELON un TEMPLATE */
$email = (new TemplatedEmail())
    ->from('neoflipswizards@gmail.com')
    ->to(new Address(''))
    ->subject('Thanks for signing up!')

    // path of the Twig template to render
    ->htmlTemplate('emails/newsletter.html.twig')

    // pass variables (name => value) to the template
    ->context([
        'expiration_date' => new \DateTime('+7 days'),
        'username' => 'foo',
    ])


;
        $mailer->send($email);

        // ...
    }
}