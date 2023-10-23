<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Process\Process;
use App\Repository\SubscriberRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductCartRepository;
use App\Repository\PaymentRepository;
use App\Repository\ProductPropertyRepository;
use App\Entity\Subscriber;
use App\Entity\ProductProperty;
use App\Entity\Property;
use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Entity\Product;
use App\Entity\ProductCart;
use App\Entity\Payment;
use App\Form\SubscriberFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Twig\Environment;
use Chat\Gpt\Chat;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use \stdClass;



#[AsController]
class NewController extends AbstractController
{

    #[Route('/new', name: 'app_new')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        return $this->render('new/index.html.twig', [
            'controller_name' => 'NewController',
        ]);
    }

    
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('new/index.html.twig', [
            'controller_name' => 'NewController',
        ]);
    }

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager, private ManagerRegistry $doctrine)
    {
        $this->entityManager = $entityManager;
        

    }



    /**
     * @Route("/subscribe", name="get_subscription_form", methods={"GET"})
     */
    public function get_subscription_form()
    {
        return $this->render('new/index.html.twig');
    }

    /**
     * @Route(path="/search-products?skintype={skintype}&skinproblem={skinproblem}", name="search-products", methods={"GET"})
     */
    public function set_search_products(SessionInterface $session, $skintype = null, $skinproblem = null)
    {
        
        $products = $this->doctrine
        ->getRepository(Product::class)
        ->findProductsBySkinTypeAndSkinProblem($skintype, $skinproblem);

        
        //function declared to get the quantity of products in the cart
        $productqty =$this->getProductquantity($session);
        //dd($productqty);

        $recommendations = $this->getRecommendations();
        /*$recommendations = $this->doctrine
                        ->getRepository(Product::class)
                        ->findOneByBenefits("Hydrate");*/

        //dd($recommendations);

        return  $this->render('new/search_products.html.twig', [
            "products" => $products,
            "recommendedproducts" => $recommendations,
            "productqty" => $productqty
            
        ]);
    }

    
    protected function getRecommendations()
    {


        $products = $this->doctrine
        ->getRepository(Product::class)
        ->findAllProducts();
        $entityManager = $this->doctrine
        ->getManager();
    
        //crreate an empty array
        $recommendedProducts = [];
        //checks in a list of products
        //For every products get productProperties
        foreach ($products as $product) {
            $productProperties = $product->getProductProperties();
        //checks in a list of productProperties
        //For every productProprties get property
            foreach($productProperties as $productProperty){
                $property = $productProperty->getProperty();
                //get benefits
                $benefits = $property->getBenefits();
    
                if (!empty($benefits)) {
                    $recommendations = $this->doctrine->getRepository(Product::class)
                        ->findOneByBenefits($benefits);

    
                    // Exclude the current product from recommendation
                    $recommendedProducts = array_filter($recommendations, function ($recommendation) use ($product) {
                        return $recommendation->getId() !== $product->getId();
                    });

                }

                
            }
            
        }
    
        return $recommendedProducts;
    }

    /**public function showRecommendations()
{
    $recommendedProducts = $this->getRecommendations();

    return $this->render('search_products.html.twig', [
        'recommendedProducts' => $recommendedProducts,
    ]);
}*/
    

    /**
     * @Route("/save-subscriber", name="save_subscriber", methods={"POST"})
     */
    
    public function save_subscriber(Request $request): Response
    {
    
        $name = $request->get("first_name");
        $skintype = $request->get("skintype");
        $skinproblem = $request->get("skinproblem");

        #$subscriber = $this->entityManager->getRepository(Subscriber::class)->findBy(['id' => $id]);
        #$em = $this->getDoctrine()->getManager();
        $this->doctrine;



        $subscriber = new Subscriber();
        $subscriber->setname($name);
        $subscriber->setskintype($skintype);
        $subscriber->setskinproblem($skinproblem);
        
       $this->entityManager->persist($subscriber);

        $this->entityManager->flush();
     
        //dd($subscriber);

       
//redirecting to search products page
        return $this->redirectToRoute('search-products',["skintype" => $skintype, "skinproblem" => $skinproblem]);


}

    /**
     * @Route(path="/subscriber/{id}", name="subscriber", methods={"GET"})
     */
 //the function below helps to return a id of a subscriber found in the database
     public function showSubscriber($id = null){

        $subscriber = $this->doctrine
            ->getRepository(Subscriber::class)
            ->find($id);

            dd($subscriber);

            //in case there is no result out we use the "if" function and do an exception
            if(!$subscriber){
                throw $this->createNotFoundException('No User Found with\'id'.$Id);
            }

            return Reponse($subscriber->getName());
         
     }

     /**
      * @Route(path="/subscriber/by-skin-problem/{skinproblem}", name="subscriber_by_skin_problem", methods={"GET"})
      */

      //function to sort out all the users having a specified problem when entered in the URL

      
        public function subscriberByskinproblem($skinproblem = null){
            $subscriber = $this->doctrine
            ->getRepository(Subscriber::class)
            ->findByskinproblem($skinproblem);

            dd($subscriber);
        }

/**
* @Route(path="/subscriber/by-skin-type/{skintype}", name="subscriber_by_skin_type", methods={"GET"})
*/

        public function subscriberByskintype($skintype = null){
            $subscriber = $this->doctrine
            ->getRepository(Subscriber::class)
            ->findByskintype($skintype);

            dd($subscriber);

        }

/**
* @Route(path="/subscriber/by-skin-problem-or-type/{skintype}/{skinproblem}", name="subscriber_by_skin_problem_or_skin_type", methods={"GET"})
*/
        public function subscriberByskinproblemOrType($skinproblem = null, $skintype = null)
        {
            $subscriberRepository = $this->doctrine
            ->getRepository(Subscriber::class);

            $subscriber = $subscriberRepository
            ->getAllByskinproblemOrskintype($skinproblem, $skintype);

            dd($subscriber);
        }

//this function permits to get the cart

        /**
         * @Route("/cart2", name="cart2", methods={"GET"})
         */

        public function cartBySessionId(Request $request)
        {
            $session = $request->getSession();

            $cart = getCart($session);

            dd($cart);

            $this->entityManager->flush();

            return $this->render('details.html.twig', [
                'cart' => $cart,
            ]);
        }

        protected function getCart($session)
        {
            $sessionId = $session->getId();

            $cart = $this->doctrine
            ->getRepository(Cart::class)
            ->findOneBySessionId($sessionId);


            #$em = $this->getDoctrine()->getManager();
            $em = $this->doctrine;
            
            if(!$cart){
                $cart_entity = new Cart();
                $cart_entity->setSessionId($sessionId);

                $this->entityManager->persist($cart_entity);
                $this->entityManager->flush();



            //dd($cart);

                return $cart_entity;

            }
            return $cart;
         
        }

        //to fetch product from database
        protected function getProduct($id)
        {

            $product = $this->doctrine
            ->getRepository(Product::class)
            ->findProductById($id);

            return $product;
        }


        //permits to have the details of the product like name, price
        /**
         * @Route("/cart", name="cart")
         */
         public function indexs( SessionInterface $session, ProductRepository $productRepository){
             $cart = $this->getCart($session);
             
             $productCarts = $cart->getProductCarts();

             //dd($cartinfo);

             return $this->render('new/details.html.twig', [
                 'productCarts' => $productCarts,
                 'cart' => $cart
                 
             ]);

         }

        //creation of object productCart and passing/saving data in database

    /**
     * @Route(path="/cart/add/{id}", name="cart_add", methods={"POST"})
   */
        public function add(EntityManagerInterface $entityManager,Request $request, $id = null){
        
            $session = $request->getSession();

           
            $product_id =  $request->get("id");
            $cart = $this->getCart($session);
            $quantity = $request->get("quantity");
            $product = $this->getProduct($product_id);
    
            $this->doctrine;



       

//fucntion will avoid the duplication of products in the cart and only increment the quantity

            $ProductCartRepository = $this->doctrine
               ->getRepository(ProductCart::class);
            $productCart = $ProductCartRepository
               ->findProductCartsByProductAndCart($product, $cart);
    
            if ($productCart) {
                $productCart = $productCart[0];
            }else{
                $productCart = new ProductCart();
                $productCart->setproduct($product);
                $productCart->setcart($cart);
            }
            //dd($product);

            $productCart->setquantity($quantity);
            
        //dd($cart->getProductCarts()->count());


           $this->entityManager->persist($productCart);
    
            $this->entityManager->flush();

         return $this->redirectToRoute('cart');

        }

//add a function that permits to remove an item in a cart

            /**
             * @Route("/cart/remove/{id}", name="cart_remove")
             */
            public function productCart( EntityManagerInterface $entityManager,ProductCartRepository $ProductCartRepository, $id = null): Response
            {
                #$em = $this->getDoctrine()->getManager();
                $this->doctrine;

               $productCart = $entityManager->getRepository(ProductCart::class)
               ->findProductCartById($id);

               $entityManager->remove($productCart);
               $entityManager->flush();

               return $this->redirectToRoute('cart');    
             }

             //function to be able to display  the quantity of products added in the cart
              
             public function getProductquantity(SessionInterface $session){
                $cart = $this->getCart($session);
                
                $productCarts = $cart->getProductCarts();

                $productquantiy = 0;
   
                foreach ($productCarts as $product) {
                    $productquantiy += $product->getquantity();

            }
                   
                return $productquantiy;   
            }

            /**
             * @Route("get-product-quantity-in-cart", name="get_product_quantity_in_cart")
             */
            public function getProductQuantityInCart(SessionInterface $session){
                $cart = $this->getCart($session);
                
                $num = $cart->totalProducts();

                return $this->render('new\get-number-of-products-in-the-cart.html.twig', [
                    'num' => $num]);
            } 


            //display the amount of the products from cart to payment page automatically by calling the information from the database
            /**
             * @Route("payment", name="payment")
             */
            
        public function payment(Request $request)
        {

            $amount =  $request->get("amount");

            return $this->render('new\payment.html.twig',[
            'amount' => $amount]);

            
        }
    //this function permits to get information from the user when they want to process to a payment

        /**
         * @Route("user-info", name = "user_info", methods = {"POST"})
         */
   
        public function user_details(Request $request): Response
        {

            $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';

        //to generate a unique reference to every client
            $string = '';
                $max = strlen($characters) - 1;
                 for ($i = 0; $i < 9; $i++) {
                 $string .= $characters[mt_rand(0, $max)];
                }
            $amount =  $request->get("amount");
            $clientname =  $request->get("name");
            $email =  $request->get("email");
            $phonenumber =  $request->get("phonenumber");
            $status = false;

            
            #$em = $this->getDoctrine()->getManager();
            $this->doctrine;
            
            $object = new \stdClass();
            $payment = new Payment();
            $payment->setclientname($clientname);
            $payment->setamount($amount);
            $payment->setemail($email);
            $payment->setphonenumber($phonenumber);
            $payment->setreference($string);
            $payment->setstatus($status);
        
            $this->entityManager->persist($payment);

            $this->entityManager->flush();

            $response = $this->settingApi($amount, $string, $phonenumber, $clientname, $email);
            //dd($response);


            //dd($amount, $clientname, $phone_number, $email, $string);

            if (property_exists($object, 'payment_url')) {
                $paymentUrl = $object->payment_url;
        //directs the page of user info directly to my coolpay for payment
                return $this->redirect($response->payment_url);
            } 
            $payment = 'User Details';
            $response = new Response($payment);
            
            return $response;
        
            }

            //the api passed in the url is the one of "my coolpay" which permits the site to use my-coolpay as the payment method

            
            public function settingApi(string $amount, string $string, ?int $phonenumber, string $clientname, string $email )
            {
                $decoded = null;

                $url = "https://my-coolpay.com/api/7417d722-6c08-4a0a-992b-5f60a765b0db/paylink";

                $data_array = array(
                    'transaction_amount' => $amount,
                    'transaction_currency'=> "XAF",
                    'transaction_reason'=> "Bic pen",
                    'app_transaction_ref'=> $string,
                    'customer_phonenumber'=> $phonenumber,
                    'customer_name'=> $clientname,
                    'customer_email'=> $email,
                    'customer_lang'=> "en"
                );

                //$data = http_build_query($data_array);

                $ch = curl_init();

                $certificate_location = '/Program Files/OpenSSL-Win64/certs/cacert.pem';
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_array);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   

                $resp = curl_exec($ch);

                if($e = curl_error($ch)){
                    echo $e;
                }
                else{
                    $decoded = json_decode($resp);
                    //dd($decoded);
                }

               // dd($resp);

                curl_close($ch);
                

                return $decoded;
            }

      /**
      * @Route(path="/benefits/by-benefits/{benefit}", name="products_by_benefits", methods={"GET"})
      */

      //function to sort out all the product having a specified benefits when entered in the URL

      
      public function productsByproperty($benefit = null){
        $benefits = $this->doctrine
        ->getRepository(Product::class)
        ->findOneByBenefits($benefit);

        dd($benefits, $benefit);
    }

    
}
