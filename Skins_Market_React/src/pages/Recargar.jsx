import { loadStripe } from "@stripe/stripe-js";
import { Elements } from "@stripe/react-stripe-js";
import { useEffect, useState } from "react";
import CheckoutForm from "./CheckoutForm";
import { useTranslation } from "react-i18next";

function Recargar() {
  const { t } = useTranslation();

  const [monto, setMonto] = useState(10);
  const [stripePromise, setStripePromise] = useState(null);
  const [stripeError, setStripeError] = useState(null);

  useEffect(() => {
    let isMounted = true;

    const stripeKey = import.meta.env.VITE_STRIPE_KEY;

    if (!stripeKey) {
      setStripeError(t("payment.missing_key"));
      return undefined;
    }

    loadStripe(stripeKey)
      .then((stripe) => {
        if (!isMounted) return;

        if (!stripe) {
          setStripeError(t("payment.load_error"));
          return;
        }

        setStripePromise(Promise.resolve(stripe));
      })
      .catch(() => {
        if (isMounted) {
          setStripeError(t("payment.browser_block"));
        }
      });

    return () => {
      isMounted = false;
    };
  }, [t]);

  return (
    <div className="perfil-container">

      <h2>{t("payment.title")}</h2>

      <div className="mb-3">
        <label className="form-label">
          {t("payment.amount")}
        </label>

        <input
          type="number"
          className="form-control"
          value={monto}
          onChange={(e) => setMonto(e.target.value)}
          min="1"
        />
      </div>

      {stripeError && (
        <div className="alert alert-danger">
          {stripeError}
        </div>
      )}

      {!stripeError && !stripePromise && (
        <div className="alert alert-warning">
          {t("payment.loading")}
        </div>
      )}

      {!stripeError && stripePromise && Number(monto) > 0 && (
        <Elements stripe={stripePromise}>
          <CheckoutForm
            monto={monto}
            onSuccess={() => (window.location.href = "/perfil")}
          />
        </Elements>
      )}
    </div>
  );
}

export default Recargar;