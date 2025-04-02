import {useLoginForm} from "./useLoginForm"


export default function LoginForm({ action }: { action: string }) {
    const { register, handleSubmit, errors, onSubmit } = useLoginForm(action);

    return (
        <form onSubmit={handleSubmit(onSubmit)}>
            <div>
                <label>Email</label>
                <input type="email" {...register("username")} />
                {errors.username && <p>{errors.username.message}</p>}
            </div>

            <div>
                <label>Mot de passe</label>
                <input type="password" {...register("password")} />
                {errors.password && <p>{errors.password.message}</p>}
            </div>

            <input type="hidden" name="_csrf_token"
                   value="{{ csrf_token('authenticate') }}"
            />

            <button type="submit">S'inscrire</button>
        </form>
    );
}