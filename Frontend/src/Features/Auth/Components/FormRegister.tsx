
import { Controller } from 'react-hook-form'
import useFormRegister from '../hooks/Form/useFormRegister'
import TextField from '../../../Utils/Components/TextField/TextField';
import type { IButtonProps, ITextFieldProps } from '../../../Utils/Components/types';
import Button from '../../../Utils/Components/Button/Button';
import { Link } from 'react-router';

const FormRegister = () => {
    const {control, onSubmit} = useFormRegister();

    const emailProps:ITextFieldProps = {
        label: 'Email',
        type: 'email',
        onChange:()=>{},
        placeholder:'name@gmail.com',
    }
    const passwordProps: ITextFieldProps = {
        label: 'Password',
        type: 'password',
        onChange: () => {},
        placeholder: '••••••••••'
    }
    const password_confirmationProps: ITextFieldProps = {
        ...passwordProps,
        label: 'Confirm Password'
    } 
    const nameProps: ITextFieldProps = {
        label: 'Username',
        type: 'text',
        onChange:()=>{},
        placeholder: 'MediaPart'
    }
    const buttonProps: IButtonProps = {
        textContent: 'Sign up',
        type: 'submit',
        icon:'',
        className:'w-[60%] m-auto mt-5 bg-(--bg-primary) text-white py-2'
    }
  return (
    <form onSubmit={onSubmit} className='w-[80%] md:w-[50%] sm:w-[60%] lg:w-[35%] m-auto h-max mt-10'>
        <h1 className='font-semibold text-2xl'>Ready to get informed!</h1>
        <hr className='my-3 border-gray-300' />
        <Controller
            name='email'
            control={control}
            rules={{required:true}}
            render={({field, fieldState: {error}}) => <TextField {...emailProps} {...field} errorMessage={field.value ? error?.message :  ''}/>}
        />
        <Controller
            name='name'
            control={control}
            rules={{required:true}}
            render={({field, fieldState: {error}}) => <TextField {...nameProps} {...field} errorMessage={field.value ? error?.message : ''} />}
        />
        <Controller
            name='password'
            control={control}
            rules={{required:true}}
            render={({field, fieldState: {error}}) => <TextField {...passwordProps} {...field} errorMessage={field.value ? error?.message : ''} />}
        />
        <Controller
            name='password_confirmation'
            control={control}
            rules={{required:true}}
            render={({field, fieldState: {error}}) => <TextField {...password_confirmationProps} {...field} errorMessage={field.value ? error?.message : ''}/>}
        />
        <div className='flex gap-3 items-baseline pl-1 mt-5'>
            <input type="checkbox" id='check' className='scale-130 translate-y-[30%]' required/>
            <label htmlFor="check">By pursuing you agree with <a className='text-blue-500'>General Terms of use</a> and the <a className='text-blue-500'>Terms and Services.</a></label>
        </div>
        
        <Button {...buttonProps} />        
        <p className='mt-5 text-center'>Already have an account? <Link to='/login' className='text-blue-500'>Login</Link></p>
    </form>
  )
}

export default FormRegister