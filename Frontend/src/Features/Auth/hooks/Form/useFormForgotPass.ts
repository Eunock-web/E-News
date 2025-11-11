import { zodResolver } from '@hookform/resolvers/zod';
import { useForm } from 'react-hook-form';
import { ForgotPassSchema, type ForgotPassInput } from '../../../types';
import { useNavigate } from 'react-router';
import useMutationForgotPass from '../Mutation/useMutationForgotPass';

const useFormForgotPass = () => {
    const form = useForm<ForgotPassInput>({
       resolver: zodResolver(ForgotPassSchema), 
       mode: 'onChange'       
    });         
    const navigate = useNavigate();
    const mutation = useMutationForgotPass();

    const onSubmit = async (data: ForgotPassInput) => {
        await mutation.mutateAsync(data);
        if(mutation.data?.success)
          navigate('reset-password')
    }  
    return {
       control: form.control,
       states: form.formState,
       onSubmit: form.handleSubmit(onSubmit, (data)=>console.log(data))
    }
}

export default useFormForgotPass