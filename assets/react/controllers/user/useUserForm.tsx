import * as z from "zod";
import { useState } from "react";
import UserForm from "./userForm";

const schema = z.object({
    firstname: z.string(),
    lastname: z.string(),
    job: z.string(),
    linkedin: z.string(),
    age: z.string(),
    city: z.string(),
    phone: z.string().min(10, "The phone number is invalid"),
});

type userFormProps = {
    firstname: string;
    lastname: string;
    job: string;
    linkedin: string;
    age: string;
    city: string;
    phone: string;
};

export function useUserForm(props: userFormProps, action: string) {
    const [form, setForm] = useState({
        firstname: props.firstname || '',
        lastname: props.lastname || '',
        job: props.job || '',
        linkedin: props.linkedin || '',
        age: props.age || 0,
        city: props.city || '',
        phone: props.phone || '',
    });

    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState('');

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setSaving(true);
        setMessage('');

        try {
            const response = await fetch(action, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(form),
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erreur lors de l’enregistrement');

            setMessage(data.message || 'Enregistré avec succès !');
        } catch (error: any) {
            setMessage(error.message);
        } finally {
            setSaving(false);
        }
    };

    return (
        <UserForm
            form={form}
            onChange={handleChange}
            onSubmit={handleSubmit}
            saving={saving}
            message={message}
        />
    );
}