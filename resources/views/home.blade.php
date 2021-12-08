@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <section>
                        <div class="row mb-4">
                            
                            @if(Auth::user()->subscription('monthly'))
                                <div class="col-md-5">
                                    <h3>Yearly Plan</h3>
                                    <h5>$100.00</h5>
                                </div>
                                <h4 class="text-red mt-4" >You are subscribed to monthly Plan</h4>
                             
                            @elseif(Auth::user()->subscription('yearly'))
                                <div class="col-md-5">
                                    <h3>Monthly Plan</h3>
                                    <h5>$10.00</h5>
                                </div>
                                <h4 class="mt-4" >You are subscribed to yearly Plan</h4>
                            @else
                                <div class="col-md-5">
                                    <h3>Monthly Plan</h3>
                                    <h5>$10.00</h5>
                                </div>
                                <div class="col-md-5">
                                    <h3>Yearly Plan</h3>
                                    <h5>$100.00</h5>
                                </div>
                                <h4 class="mt-4" >You are not subscribed to any Plan</h4>
                            @endif
                        </div>

                        @if(Auth::user()->subscription('yearly') || !Auth::user()->subscription('monthly'))
                        <form action="{{route('pay','monthly')}}" method="POST" id="stripe">
                            @csrf
                            <div class="form-group">
                                <input type="text" placeholder="Card holder name" class="form-control" id="card-holder-name" />
                            </div>
                            <div id="card-element"></div>
                            <input type="hidden" id="pmethod" name="pmethod" value="" />
                            
                            <button  id="card-button" class="mt-3 btn btn-primary">
                                Activate Monthly Plan
                            </button>
                            
                        </form>
                        @endif
                        @if(Auth::user()->subscription('monthly') || !Auth::user()->subscription('yearly'))
                            <form action="{{route('pay','yearly')}}" method="POST" id="stripe">
                                @csrf
                                
                                <input type="hidden" id="pmethod" name="pmethod" value="" />
                                <button  id="card-button" class="mt-3 btn btn-success">
                                    Activate Yearly Plan
                                </button>

                            </form>
                        @endif

                        

                        <script src="https://js.stripe.com/v3/"></script>
                        <script>
                            const stripe = Stripe('{{ env("STRIPE_KEY") }}');
                            const elements = stripe.elements();
                            const cardElement = elements.create('card');
                            cardElement.mount('#card-element');
                            const cardHolderName = document.getElementById('card-holder-name'); 
                            const form = document.getElementById('stripe');
                            form.addEventListener('submit', async (e) => {
                                e.preventDefault();
                                const {paymentMethod, error} = await stripe.createPaymentMethod(
                                    'card' , cardElement, {
                                        billing_details : { name: cardHolderName.value}
                                    }
                                );
                                if(error){

                                }else{
                                    console.log('card verified successfully');
                                    console.log(paymentMethod.id);
                                    document.getElementById('pmethod').setAttribute('value', paymentMethod.id);
                                    form.submit();
                                }
                            });
                        </script>
                    </section>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
