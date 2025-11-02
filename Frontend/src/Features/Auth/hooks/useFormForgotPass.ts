import { zodResolver } from '@hookform/resolvers/zod';
import { useForm } from 'react-hook-form';
import { ForgotPassSchema, type ForgotPassInput } from '../../types';

const useFormForgotPass = () => {
    const form = useForm<ForgotPassInput>({
       resolver: zodResolver(ForgotPassSchema), 
       mode: 'onChange'       
    });    
    const onSubmit = (data: ForgotPassInput) => {
       console.log(data)
    }  
    return {
       control: form.control,
       states: form.formState,
       onSubmit: form.handleSubmit(onSubmit, (data)=>console.log(data))
    }
}

export default useFormForgotPass