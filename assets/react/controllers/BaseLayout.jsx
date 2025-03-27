import React from "react";

const BaseLayout = ({ children }) => {
    return (
        <div className="relative min-h-screen flex flex-col bg-gray-100">
            {/* Background */}
            <div
                className="absolute inset-0 bg-cover bg-center opacity-20"
                style={{ backgroundImage: "url('/images/background.jpg')" }}
            ></div>

            {/* Contenu principal */}
            <div className="relative z-10 flex flex-col min-h-screen">
                {/* Header */}
                <header className="bg-blue-600 text-white p-4 shadow-md">
                    <div className="container mx-auto flex justify-between items-center">
                        <h1 className="text-xl font-bold">Mon Application</h1>
                        <nav>
                            <ul className="flex gap-4">
                                <li><a href="/" className="hover:underline">Accueil</a></li>
                                <li><a href="/about" className="hover:underline">À propos</a></li>
                            </ul>
                        </nav>
                    </div>
                </header>

                {/* Contenu dynamique */}
                <main className="flex-grow container mx-auto p-6">{children}</main>

                {/* Footer */}
                <footer className="bg-blue-800 text-white p-4 text-center">
                    <p>&copy; {new Date().getFullYear()} Mon Application</p>
                </footer>
            </div>
        </div>
    );
};

export default BaseLayout;