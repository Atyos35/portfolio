import React from 'react';

type FormProps = {
    form: {
        firstname: string;
        lastname: string;
        job: string;
        linkedin: string;
        age: string;
        city: string;
        phone: string;
        csrfToken: string;
    };
    onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
    onSubmit: (e: React.FormEvent) => void;
    saving: boolean;
    message: string;
};

export default function UserForm({form, onChange, onSubmit, saving, message}: FormProps) {
    return (
        <form onSubmit={onSubmit}>
            <h2>Personal informations</h2>

            {[
                'firstname',
                'lastname',
                'job',
                'linkedin',
                'age',
                'city',
                'phone'].map((field) => (
                <div key={field}>
                    <label htmlFor={field}>{field}</label>
                    <input
                        type="text"
                        id={field}
                        name={field}
                        value={(form as any)[field]}
                        onChange={onChange}
                    />
                </div>
            ))}

            <button type="submit" disabled={saving}>
                {saving ? 'Saving...' : 'Save'}
            </button>

            {message && <p>{message}</p>}
        </form>
    );
}
