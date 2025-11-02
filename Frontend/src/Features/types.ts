import z  from "zod/v4";
import type { ITextFieldProps } from "../Utils/Components/types";

export const LoginSchema = z.object({
    email: z.email(),
    password: z.string().min(8, "Incorrect Password!")
})
export type LoginInputs = z.infer<typeof LoginSchema>;

export const RegisterSchema = z.object({
    username: z.string().min(2, "Enter a valid username!"),
    email: z.email("Invalid Email!"),
    password: z.string().min(8, "8 characters minimum!"),
    confirmPassword: z.string()
}).refine(schema => schema.password === schema.confirmPassword, {
    error: "Passwords don't match!",
    path:['confirmPassword'],
    when: (payload) => RegisterSchema.pick({confirmPassword: true}).safeParse(payload.value).success 
})
export type RegisterInputs = z.infer<typeof RegisterSchema>;

export const ForgotPassSchema = z.object({
    email: z.email("Invalid email!")
});
export type ForgotPassInput = z.infer<typeof ForgotPassSchema>;

export const RetrieveCodeSchema = z.object({
    newPassword: z.string().min(8, "8 characters minimum!"),
    confirmPassword: z.string()
}).refine( schema => schema.newPassword === schema.confirmPassword, {
    error: "Password don't match!",
    path: ['confirmPassword'],
    when: (payload) => RetrieveCodeSchema.pick({newPassword:true}).safeParse(payload.value).success
});
export type RetrieveCodeInputs = z.infer<typeof RetrieveCodeSchema>;

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