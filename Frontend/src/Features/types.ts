import z  from "zod/v4";

export const LoginSchema = z.object({
    email: z.email(),
    password: z.string().min(8, "Incorrect Password!")
})
export type LoginInputs = z.infer<typeof LoginSchema>;

export const RegisterSchema = z.object({
    username: z.string().min(2, "Enter a valid username!"),
    email: z.email(),
    password: z.string().min(8, "Minimum 8 characters!").refine(pass => pass.includes('')),
    confirmPassword: z.string()
}).refine(schema => schema.password === schema.confirmPassword, {
    error: "Passwords don't match!",
    path:['confirmPassword'],
    when: (payload) => RegisterSchema.pick({password: true, confirmPassword: true}).safeParse(payload.value).success 
})
export type RegisterInputs = z.infer<typeof RegisterSchema>;