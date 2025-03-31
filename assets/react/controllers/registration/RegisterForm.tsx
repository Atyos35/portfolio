import React from "react";
import { useRegisterForm } from "./useRegisterForm";

export default function RegisterForm({ action }: { action: string }) {
    const { register, handleSubmit, errors, onSubmit } = useRegisterForm(action);

    return (
        <form onSubmit={handleSubmit(onSubmit)}>
            <div>
                <label>Email</label>
                <input type="email" {...register("email")} />
                {errors.email && <p>{errors.email.message}</p>}
            </div>

            <div>
                <label>Firstname</label>
                <input type="text" {...register("firstname")} />
                {errors.firstname && <p>{errors.firstname.message}</p>}
            </div>

            <div>
                <label>Lastname</label>
                <input type="text" {...register("lastname")} />
                {errors.lastname && <p>{errors.lastname.message}</p>}
            </div>

            <div>
                <label>Job</label>
                <input type="text" {...register("job")} />
                {errors.job && <p>{errors.job.message}</p>}
            </div>

            <div>
                <label>LinkedIn</label>
                <input type="text" {...register("linkedin")} />
                {errors.linkedin && <p>{errors.linkedin.message}</p>}
            </div>

            <div>
                <label>Age</label>
                <input type="number" {...register("age")} />
                {errors.age && <p>{errors.age.message}</p>}
            </div>

            <div>
                <label>City</label>
                <input type="text" {...register("city")} />
                {errors.city && <p>{errors.city.message}</p>}
            </div>

            <div>
                <label>Phone</label>
                <input type="text" {...register("phone")} />
                {errors.phone && <p>{errors.phone.message}</p>}
            </div>

            <div>
                <label>Mot de passe</label>
                <input type="password" {...register("plainPassword")} />
                {errors.plainPassword && <p>{errors.plainPassword.message}</p>}
            </div>

            <button type="submit">S'inscrire</button>
        </form>
    );
}