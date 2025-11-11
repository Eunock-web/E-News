import useFormForgotPass from '../hooks/Form/useFormForgotPass'
import { Controller } from 'react-hook-form';
import TextField from '../../../Utils/Components/TextField/TextField';
import { emailProps } from '../../types';
import Button from '../../../Utils/Components/Button/Button';
import { Link } from 'react-router';

const FormForgotPass = () => {
    const {control, onSubmit} = useFormForgotPass();

    return (
    <form className='w-[30%] m-auto h-max' onSubmit={onSubmit}>
        <h1 className='text-2xl'>Identify your account</h1>
        <p className='mt-5 text-gray-400 '>Enter your registered email to identify your account and reset your password.</p>
        <Controller 
            control={control}
            name='email'
            rules={{required:true}}
            render={({field, fieldState: {error}}) => <TextField {...emailProps} {...field} errorMessage={field.value ? error?.message : ''} />}
        />
        <div className='flex gap-3 justify-end w-full items-center text-white mt-5'>
            <Link className='w-[35%] bg-gray-400 rounded-md py-2 h-max font-semibold text-center duration-200 hover:scale-95 hover:opacity-70' to={'/login'}>Cancel</Link>
            <Button textContent='Send' className='w-[35%] bg-(--bg-primary) py-2 text-white' type='submit' icon=''/>            
        </div>
        
    </form>
  )
}

export default FormForgotPass