import React, { useEffect, useState } from 'react';
import Modal from '../modal/modal';
import { useUserForm } from './useUserForm';
import useOpenModal from '../modal/useOpenModal';

type Props = {
    action: string;
};

type User = {
    firstname: string;
    lastname: string;
    email: string;
    job: string;
    linkedin: string;
    age: string;
    city: string;
    phone: string;
};

const emptyUser: User = {
    firstname: '',
    lastname: '',
    email: '',
    job: '',
    linkedin: '',
    age: '',
    city: '',
    phone: ''
};

export default function UserGet({ action }: Props) {
    const [user, setUser] = useState<User | null>(null);
    const { isOpen, open, close } = useOpenModal();

    const userForm = useUserForm(user || emptyUser, action);

    useEffect(() => {
        const fetchUser = async () => {
            try {
                const response = await fetch(action, {
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json' },
                });

                if (!response.ok) {
                    throw new Error('Error while getting User infos');
                }

                const data = await response.json();
                setUser(data);
            } catch (error) {
                console.error(error);
            }
        };

        fetchUser();
    }, [action]);

    if (!user) return <div>Chargement...</div>;

    return (
        <div className="space-y-6" onClick={open}>
            <div className="pt-4 w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div className="pt-4 flex flex-col items-center pb-10">
                    <img
                        className="w-24 h-24 mb-3 rounded-full shadow-lg"
                        src="/images/pp.jpg"
                        alt="Profile picture"
                    />
                    <h5 className="text-xl font-medium text-gray-900 dark:text-white">
                        {user.firstname} {user.lastname}
                    </h5>
                    <span className="text-sm text-gray-500 dark:text-gray-400">{user.job}</span>
                    <span className="text-sm text-gray-500 dark:text-gray-400">{user.linkedin}</span>
                </div>
            </div>

            <div className="pt-4 pb-6 w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h5 className="text-xl font-medium text-gray-900 dark:text-white text-center">
                    {user.firstname} {user.lastname}
                </h5>
                <span className="block mb-4 text-sm text-center text-gray-500 dark:text-gray-400">
                    {user.job}
                </span>

                <div className="px-6 space-y-2">
                    <div className="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                        <span><strong>E-mail :</strong> {user.email}</span>
                        <span><strong>City :</strong> {user.city}</span>
                    </div>
                    <div className="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                        <span><strong>Age :</strong> {user.age} years old</span>
                        <span><strong>Phone :</strong> {user.phone}</span>
                    </div>
                </div>
            </div>

            {isOpen && (
                <Modal isOpen={isOpen} onClose={close}>
                    {userForm}
                </Modal>
            )}
        </div>
    );
}