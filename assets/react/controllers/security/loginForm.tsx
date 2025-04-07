import { useLoginForm } from "./useLoginForm";

interface LoginFormProps {
    action: string;
    csrfToken: string;
}

export default function LoginForm({ action, csrfToken }: LoginFormProps) {
    const { register, handleSubmit, errors, onSubmit } = useLoginForm(action, csrfToken);

    return (
        <form onSubmit={handleSubmit(onSubmit)}>
            <div>
                <label>Email</label>
                <input type="email" {...register("username")} />
            </div>

            <div>
                <label>Mot de passe</label>
                <input type="password" {...register("password")} />
            </div>

            <input type="hidden" name="_csrf_token" value={csrfToken} />

            <button type="submit">Se connecter</button>
        </form>
    );
}