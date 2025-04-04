import { useLoginForm } from "./useLoginForm";

interface LoginFormProps {
    action: string;
    csrfToken: string;
}

export default function LoginForm({ action, csrfToken }: LoginFormProps) {
    const { register, handleSubmit, errors, onSubmit } = useLoginForm(action);

    return (
        <form onSubmit={handleSubmit(onSubmit)}>
            <div>
                <label>Email</label>
                <input type="email" {...register("username")} />
                {/*{errors.username && <p>{errors.username}</p>}*/}
            </div>

            <div>
                <label>Mot de passe</label>
                <input type="password" {...register("password")} />
                {/*{errors.password && <p>{errors.password}</p>}*/}
            </div>

            <input type="hidden" name="_csrf_token" value={csrfToken} />

            <button type="submit">Se connecter</button>
        </form>
    );
}
