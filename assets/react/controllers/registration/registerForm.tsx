import { useState } from "react";
import { useRegisterForm } from "./useRegisterForm";
import { motion } from "framer-motion";
import HidePwIcon from '../components/atoms/hidePwIcon';
import ShowPwIcon from '../components/atoms/showPwIcon';

interface RegisterFormProps {
    action: string;
    csrfToken: string;
}

export default function RegisterForm({ action, csrfToken }: RegisterFormProps) {
    const { register, handleSubmit, errors, onSubmit, watch } = useRegisterForm(action, csrfToken);
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    const password = watch("plainPassword");

    return (
        <motion.div
            className="mt-8 p-8 rounded-2xl shadow-md w-96 section-background"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -20 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
        >
            <h1 className="text-2xl font-semibold mb-6 text-center">Inscription</h1>
            <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                <div className="flex flex-wrap -mx-2">
                    <div className="w-1/2 px-2 mb-4">
                        <label className="block text-sm font-medium mb-1 text-black">Email</label>
                        <input
                            type="email"
                            {...register("email")}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        />
                        {errors.email && <p className="text-red-500 text-sm mt-1">{errors.email.message}</p>}
                    </div>

                    <div className="w-1/2 px-2 mb-4">
                        <label className="block text-sm font-medium mb-1 text-black">Prénom</label>
                        <input
                            type="text"
                            {...register("firstname")}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        />
                        {errors.firstname && <p className="text-red-500 text-sm mt-1">{errors.firstname.message}</p>}
                    </div>

                    <div className="w-1/2 px-2 mb-4">
                        <label className="block text-sm font-medium mb-1 text-black">Nom</label>
                        <input
                            type="text"
                            {...register("lastname")}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        />
                        {errors.lastname && <p className="text-red-500 text-sm mt-1">{errors.lastname.message}</p>}
                    </div>

                    <div className="w-1/2 px-2 mb-4">
                        <label className="block text-sm font-medium mb-1 text-black">Poste</label>
                        <input
                            type="text"
                            {...register("job")}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        />
                        {errors.job && <p className="text-red-500 text-sm mt-1">{errors.job.message}</p>}
                    </div>

                    <div className="w-1/2 px-2 mb-4">
                        <label className="block text-sm font-medium mb-1 text-black">LinkedIn</label>
                        <input
                            type="text"
                            {...register("linkedin")}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        />
                        {errors.linkedin && <p className="text-red-500 text-sm mt-1">{errors.linkedin.message}</p>}
                    </div>

                    <div className="w-1/2 px-2 mb-4">
                        <label className="block text-sm font-medium mb-1 text-black">Âge</label>
                        <input
                            type="number"
                            {...register("age")}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        />
                        {errors.age && <p className="text-red-500 text-sm mt-1">{errors.age.message}</p>}
                    </div>

                    <div className="w-1/2 px-2 mb-4">
                        <label className="block text-sm font-medium mb-1 text-black">Ville</label>
                        <input
                            type="text"
                            {...register("city")}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        />
                        {errors.city && <p className="text-red-500 text-sm mt-1">{errors.city.message}</p>}
                    </div>

                    <div className="w-1/2 px-2 mb-4">
                        <label className="block text-sm font-medium mb-1 text-black">Téléphone</label>
                        <input
                            type="text"
                            {...register("phone")}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        />
                        {errors.phone && <p className="text-red-500 text-sm mt-1">{errors.phone.message}</p>}
                    </div>
                </div>

                <div>
                    <label className="block text-sm font-medium mb-1 text-black">Mot de passe</label>
                    <div className="relative">
                        <input
                            type={showPassword ? "text" : "password"}
                            {...register("plainPassword")}
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
                    {errors.plainPassword && <p className="text-red-500 text-sm mt-1">{errors.plainPassword.message}</p>}
                </div>
                
                <div>
                    <label className="block text-sm font-medium mb-1 text-black">Confirmer le mot de passe</label>
                    <div className="relative">
                        <input
                            type={showConfirmPassword ? "text" : "password"}
                            {...register("confirmPassword")}
                            className="w-full px-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        />
                        <button
                            type="button"
                            onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                            className="absolute right-3 top-2.5 text-gray-600 hover:text-gray-900"
                            aria-label={showConfirmPassword ? "Masquer confirmation" : "Afficher confirmation"}
                        >
                            {showConfirmPassword ? <ShowPwIcon /> : <HidePwIcon />}
                        </button>
                    </div>
                    {errors.confirmPassword && <p className="text-red-500 text-sm mt-1">{errors.confirmPassword.message}</p>}
                </div>

                <button
                    type="submit"
                    className="w-full py-2 mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md"
                >
                    S'inscrire
                </button>
            </form>
        </motion.div>
    );
}