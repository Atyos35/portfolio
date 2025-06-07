import React from "react";
import { Toaster } from "react-hot-toast";

export default function ToasterComponent() {
  return (
    <Toaster
      position="top-center"
      reverseOrder={false}
      toastOptions={{
        style: {
          background: "rgba(255, 255, 255, 0.6)",
          color: "#000",
          padding: "16px 24px",
          fontSize: "18px",
          maxWidth: "480px",
          boxShadow: "0 4px 14px rgba(0,0,0,0.1)",
          borderRadius: "12px",
          backdropFilter: "blur(8px)"
        }
      }}
    />
  );
}