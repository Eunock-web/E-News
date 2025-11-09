import { zodResolver } from '@hookform/resolvers/zod';
import { useForm } from 'react-hook-form';
import { ForgotPassSchema, type ForgotPassInput } from '../../types';
import { useNavigate } from 'react-router';
import useNotificationManager from '../../../Utils/Components/Notification/hooks/useNotificationManager';

const useFormForgotPass = () => {
    const form = useForm<ForgotPassInput>({
       resolver: zodResolver(ForgotPassSchema), 
       mode: 'onChange'       
    });         
    const { notify } = useNotificationManager();
    // const navigate = useNavigate();

    const onSubmit = (data: ForgotPassInput) => {
        
        console.log(data);
    }  
    return {
       control: form.control,
       states: form.formState,
       onSubmit: form.handleSubmit(onSubmit, (data)=>console.log(data))
    }
}

export default useFormForgotPass