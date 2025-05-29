import { useLoginForm } from "./useLoginForm";
import { motion } from "framer-motion";
import { useState } from "react";
import ShowPwIcon from "../components/atoms/showPwIcon";
import HidePwIcon from "../components/atoms/hidePwIcon";

interface LoginFormProps {
    action: string;
    csrfToken: string;
}

export default function LoginForm({ action, csrfToken }: LoginFormProps) {
    const { register, handleSubmit, onSubmit } = useLoginForm(action, csrfToken);
    const [showPassword, setShowPassword] = useState(false);

    return (
        <motion.div
            className="p-8 rounded-2xl shadow-md w-96 section-background"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -20 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
        >
            <h1 className="text-2xl font-semibold mb-6 text-center">Connexion</h1>
            <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                <div>
                    <label className="block text-sm font-medium mb-1 text-black">Email</label>
                    <input
                        type="email"
                        {...register("username")}
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                    />
                </div>

                <div>
                    <label className="block text-sm font-medium mb-1 text-black">Mot de passe</label>
                    <div className="relative">
                        <input
                            type={showPassword ? "text" : "password"}
                            {...register("password")}
                            className="w-full px-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        />
                        <button
                            type="button"
                            onClick={() => setShowPassword(!showPassword)}
                            className="absolute right-3 top-2.5 text-gray-600 hover:text-gray-900"
                            aria-label={showPassword ? "Masquer mot de passe" : "Afficher mot de passe"}
                        >
                            {showPassword ? <ShowPwIcon /> : <HidePwIcon />}
                        </button>
                    </div>
                </div>

                <button
                    type="submit"
                    className="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition"
                >
                    Se connecter
                </button>
            </form>
            <div className="mt-4 text-center">
                <button
                    onClick={() => Turbo.visit("/register")}
                    className="text-blue-600 hover:underline text-sm"
                >
                    S'inscrire
                </button>
            </div>
        </motion.div>
    );
}