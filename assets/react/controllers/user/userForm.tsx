import React from 'react';
import { FieldErrors, UseFormRegister, UseFormHandleSubmit } from "react-hook-form";
import {UserFormInput, useUserForm} from "./useUserForm";

type UserFormProps = {
    register: UseFormRegister<UserFormInput>;
    handleSubmit: UseFormHandleSubmit<UserFormInput>;
    onSubmit: (data: UserFormInput) => void;
    errors: FieldErrors<UserFormInput>;
    isSubmitting: boolean;
    action: string;
    csrfToken: string;
    initialValues: UserFormInput;
};

export default function UserForm({
    action,
    csrfToken,
    isSubmitting,
    initialValues}: UserFormProps) {
    const { register, handleSubmit, errors, onSubmit } = useUserForm(action, csrfToken);
    return (
        <form onSubmit={handleSubmit(onSubmit)}>
            <h2>Personal informations</h2>

            <div>
                <label htmlFor="firstname">Firstname</label>
                <input id="firstname"
                       {...register("firstname")}
                       defaultValue={initialValues.firstname} />
                {errors.firstname && <p>{errors.firstname.message}</p>}
            </div>

            <div>
                <label htmlFor="lastname">Lastname</label>
                <input id="lastname"
                       {...register("lastname")}
                       defaultValue={initialValues.lastname} />
                {errors.lastname && <p>{errors.lastname.message}</p>}
            </div>

            <div>
                <label htmlFor="job">Job</label>
                <input id="job"
                       {...register("job")}
                       defaultValue={initialValues.job} />
                {errors.job && <p>{errors.job.message}</p>}
            </div>

            <div>
                <label htmlFor="linkedin">LinkedIn</label>
                <input id="linkedin"
                       {...register("linkedin")}
                       defaultValue={initialValues.linkedin} />
                {errors.linkedin && <p>{errors.linkedin.message}</p>}
            </div>

            <div>
                <label htmlFor="age">Age</label>
                <input id="age"
                       {...register("age")}
                       defaultValue={initialValues.age} />
                {errors.age && <p>{errors.age.message}</p>}
            </div>

            <div>
                <label htmlFor="city">City</label>
                <input id="city"
                       {...register("city")}
                       defaultValue={initialValues.city} />
                {errors.city && <p>{errors.city.message}</p>}
            </div>

            <div>
                <label htmlFor="phone">Phone</label>
                <input id="phone"
                       {...register("phone")}
                       defaultValue={initialValues.phone} />
                {errors.phone && <p>{errors.phone.message}</p>}
            </div>

            <button type="submit" disabled={isSubmitting}>
                {isSubmitting ? "Saving..." : "Save"}
            </button>
        </form>
    );
}