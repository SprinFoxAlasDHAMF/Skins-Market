import { loadStripe } from "@stripe/stripe-js";
import { Elements } from "@stripe/react-stripe-js";
import { useEffect, useState } from "react";
import CheckoutForm from "./CheckoutForm";

function Recargar() {
  const [monto, setMonto] = useState(10);
  const [stripePromise, setStripePromise] = useState(null);
  const [stripeError, setStripeError] = useState(null);

  useEffect(() => {
    let isMounted = true;

    const stripeKey = import.meta.env.VITE_STRIPE_KEY;

    if (!stripeKey) {
      setStripeError("Falta configurar VITE_STRIPE_KEY en el frontend.");
      return undefined;
    }

    loadStripe(stripeKey)
      .then((stripe) => {
        if (!isMounted) return;

        if (!stripe) {
          setStripeError("No se pudo cargar Stripe en el navegador.");
          return;
        }

        setStripePromise(Promise.resolve(stripe));
      })
      .catch(() => {
        if (isMounted) {
          setStripeError("No se pudo cargar Stripe. Revisa si el navegador bloquea js.stripe.com.");
        }
      });

    return () => {
      isMounted = false;
    };
  }, []);

  return (
    <div className="perfil-container">
      <h2>Recargar saldo</h2>

      <div className="mb-3">
        <label className="form-label">Monto a recargar (EUR)</label>
        <input
          type="number"
          className="form-control"
          value={monto}
          onChange={(e) => setMonto(e.target.value)}
          min="1"
        />
      </div>

      {stripeError && (
        <div className="alert alert-danger" role="alert">
          {stripeError}
        </div>
      )}

      {!stripeError && !stripePromise && (
        <div className="alert alert-warning" role="alert">
          Cargando formulario de pago...
        </div>
      )}

      {!stripeError && stripePromise && Number(monto) > 0 && (
        <Elements stripe={stripePromise}>
          <CheckoutForm monto={monto} onSuccess={() => (window.location.href = "/perfil")} />
        </Elements>
      )}
    </div>
  );
}

export default Recargar;
