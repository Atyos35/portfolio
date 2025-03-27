import React, { useState } from "react";

const RegisterForm = () => {
    const [formData, setFormData] = useState({
        email: "",
        firstname: "",
        lastname: "",
        job: "",
        linkedin: "",
        age: "",
        city: "",
        phone: "",
        plainPassword: "",
    });

    const [errors, setErrors] = useState({});
    const [success, setSuccess] = useState(null);

    // Gestion du changement des inputs
    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    // Soumission du formulaire
    const handleSubmit = async (e) => {
        e.preventDefault();
        setErrors({});
        setSuccess(null);

        try {
            const response = await fetch("/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formData),
            });

            const data = await response.json();
            if (response.ok) {
                setSuccess("Inscription réussie !");
                setFormData({
                    email: "",
                    firstname: "",
                    lastname: "",
                    job: "",
                    linkedin: "",
                    age: "",
                    city: "",
                    phone: "",
                    plainPassword: "",
                });
            } else {
                setErrors(data.errors || {});
            }
        } catch (error) {
            console.error("Erreur lors de l'inscription :", error);
        }
    };

    return (
        <div className="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h2 className="text-2xl font-bold mb-4 text-gray-800">Inscription</h2>

            {success && <p className="text-green-600 mb-4">{success}</p>}

            <form onSubmit={handleSubmit} className="space-y-4">
                {Object.keys(errors).length > 0 && (
                    <div className="text-red-600">
                        {Object.values(errors).map((err, index) => (
                            <p key={index}>{err}</p>
                        ))}
                    </div>
                )}

                <input type="email" name="email" value={formData.email} onChange={handleChange} placeholder="Email" className="input" />
                <input type="text" name="firstname" value={formData.firstname} onChange={handleChange} placeholder="Prénom" className="input" />
                <input type="text" name="lastname" value={formData.lastname} onChange={handleChange} placeholder="Nom" className="input" />
                <input type="text" name="job" value={formData.job} onChange={handleChange} placeholder="Métier" className="input" />
                <input type="text" name="linkedin" value={formData.linkedin} onChange={handleChange} placeholder="Profil LinkedIn" className="input" />
                <input type="number" name="age" value={formData.age} onChange={handleChange} placeholder="Âge" className="input" />
                <input type="text" name="city" value={formData.city} onChange={handleChange} placeholder="Ville" className="input" />
                <input type="tel" name="phone" value={formData.phone} onChange={handleChange} placeholder="Téléphone" className="input" />
                <input type="password" name="plainPassword" value={formData.plainPassword} onChange={handleChange} placeholder="Mot de passe" className="input" />

                <button type="submit" className="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    S'inscrire
                </button>
            </form>
        </div>
    );
};

export default RegisterForm;