import * as z from "zod";
import {useEffect, useState} from "react";
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

type UserFormData = z.infer<typeof schema>;

type userFormProps = {
    firstname: string;
    lastname: string;
    job: string;
    linkedin: string;
    age: string;
    city: string;
    phone: string;
};

export function useUserForm(props: userFormProps, csrfToken: string) {
    const [form, setFormData] = useState<userFormProps>({ ...props });

    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState('');
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});

    useEffect(() => {
        setFormData(props);
    }, [props]);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setFormData((prev) => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setSaving(true);
        setMessage('');
        setFormErrors({});

        if (!csrfToken) {
            console.error("Token CSRF non disponible");
            return;
        }

        try {
            const payload = schema.parse(form);
            const response = await fetch('/user/2/edit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
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