<!-- 
// namespace App\Services;

// use App\Entity\Articles;
// use App\Entity\Order as EntityOrder;
// use Stripe\Order;
// use Stripe\Stripe;


// class StripeService
{
//     private $privateKey;

//     public function __construct()
//     {
//          $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
//     }

//     /**
//      * @param Articles $articles
//      * @return \Stripe\PaymentIntent
//      * @throws \Stripe\Exception\ApiErrorException
//      */
//     public function paymentIntent(Articles $articles)
//     {
//         \Stripe\Stripe::setApiKey($this->privateKey);

//         return \Stripe\PaymentIntent::create([
//             'amount'=> $articles->getPrix()*100,
//             'currency' => EntityOrder::DEVISE ,
//             'payment_method_types' =>['card']
//         ]);

//     }

//     public function paiement( $amount, $currency, $description, array $stripeParameter)
//         {
//             \Stripe\Stripe::setApiKey($this->privateKey);
//             $payement_intent = null;

//             if(isset($stripeParameter['stripeIntentIde']))
//             {
//                 $payement_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
//             }

//             if($stripeParameter['stripeIntentId'] === 'succedded')
//             {
                
//             }else{
//                 $payement_intent->cancel();
//             }

//             return $payement_intent;

//         }
//     /**
//      * @param array $stripeParameter
//      * @param Articles $articles
//      * @return \Stripe\PaymentIntent|null
//      */
//     public function stripe(array $stripeParameter, Articles $articles)
//     {
//         return $this->paiement(
//             $articles->getPrix()*100,
//             EntityOrder::DEVISE,
//             $articles->getArticle(),
//             $stripeParameter
//         );
//     }
    
    // public function payement()
    // {
        // if(isset($_POST['prix']) && !empty($_POST['prix'])){
                // permets de charger toute la bibliothèque de stripe
                // require_once('vendor/autoload.php');
                
                // $prix = (float)$_POST['prix'];
                // dd('prix');
                
                //  on instancie Stripe
                // \Stripe\Stripe::setApiKey('sk_test_51JlA15DnhjURuLLqEC9bDSLfcauQ5d4jltdhBlHnHj4y8kY1pqhyZc9dbFooWUSbUiffqJCnLZzK7hQjPaGjK5jS00V2NFZSc7');
                
                // $intent = \Stripe\PaymentIntent::create([
                //     'amount' => $prix*100,
                //     'currency' => 'eur'
                // ]);

                // }else{
                //     echo "testjjnkjnk";
                    // header('Location: index.php');
                // }
                // return[$intent] ;
            }
    // }
 -->
