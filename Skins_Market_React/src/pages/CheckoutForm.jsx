import { useStripe, useElements, CardElement } from "@stripe/react-stripe-js";
import { useState } from "react";
import api from "../api/api";

const CheckoutForm = ({ monto, onSuccess }) => {
  const stripe = useStripe();
  const elements = useElements();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!stripe || !elements) return;

    setLoading(true);
    setError(null);

    try {
      const amount = Number(monto);

      const { data } = await api.post("/depositar", { amount });
      const clientSecret = data.clientSecret;

      const result = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
          card: elements.getElement(CardElement),
        },
      });

      if (result.error) {
        setError(result.error.message);
        return;
      }

      if (result.paymentIntent?.status === "succeeded") {
        await api.post("/confirmar-deposito", {
          payment_intent_id: result.paymentIntent.id,
        });

        alert("Pago realizado con exito. Tu saldo ha sido actualizado.");
        onSuccess();
      }
    } catch (err) {
      console.error(err);

      const serverMessage =
        err.response?.data?.message ||
        err.response?.data?.error ||
        "Error al conectar con el servidor";

      setError(serverMessage);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="payment-form">
      <div
        style={{
          padding: "10px",
          border: "1px solid #ccc",
          borderRadius: "5px",
          background: "white",
          marginBottom: "15px",
        }}
      >
        <CardElement
          options={{
            style: {
              base: {
                fontSize: "16px",
                color: "#424770",
                "::placeholder": { color: "#aab7c4" },
              },
              invalid: { color: "#9e2146" },
            },
          }}
        />
      </div>
      {error && <div style={{ color: "red", marginBottom: "10px" }}>{error}</div>}
      <button className="btn-custom btn-success-custom" disabled={!stripe || loading}>
        {loading ? "Procesando..." : `Pagar ${monto} EUR`}
      </button>
    </form>
  );
};

export default CheckoutForm;
