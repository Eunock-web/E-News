import z  from "zod/v4";
import type { ITextFieldProps } from "../Utils/Components/types";

export const LoginSchema = z.object({
    email: z.email(),
    password: z.string().min(8, "Incorrect Password!")
})
export type LoginInputs = z.infer<typeof LoginSchema>;

export const RegisterSchema = z.object({
    name: z.string().min(2, "Enter a valid name!"),
    email: z.email("Invalid Email!"),
    password: z.string().min(8, "8 characters minimum!"),
    password_confirmation: z.string(),
    "categories_user": z.array(z.string()).default([]).optional()
}).refine(schema => schema.password === schema.password_confirmation, {
    error: "Passwords don't match!",
    path:['password_confirmation'],
    when: (payload) => RegisterSchema.pick({password_confirmation: true}).safeParse(payload.value).success 
})
export type RegisterInputs = z.infer<typeof RegisterSchema>;

export const ForgotPassSchema = z.object({
    email: z.email("Invalid email!")
});
export type ForgotPassInput = z.infer<typeof ForgotPassSchema>;

export const ResetPassSchema = z.object({
    newPassword: z.string().min(8, "8 characters minimum!"),
    password_confirmation: z.string()
}).refine( schema => schema.newPassword === schema.password_confirmation, {
    error: "Password don't match!",
    path: ['password_confirmation'],
    when: (payload) => ResetPassSchema.pick({newPassword:true}).safeParse(payload.value).success
});
export type ResetPassInputs = z.infer<typeof ResetPassSchema>;

export const emailProps: ITextFieldProps = {
    type: 'email',
    label: 'Email',
    placeholder: 'name@example.com',
    onChange: () => {}
}
  
export const passwordProps: ITextFieldProps = {
    type: 'password',
    label: 'Password',
    placeholder:'••••••••••',
    onChange: () => {}
}
