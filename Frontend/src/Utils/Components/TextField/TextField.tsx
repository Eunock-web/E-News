import { type FC } from 'react'
import type { ITextFieldProps } from '../../../../../../React-Training/Chat-App/src/Utils/Components/type';

const TextField: FC<ITextFieldProps> = ( { type, label, className, placeholder, onChange, errorMessage } ) => {
    return ( 
        <div className='flex flex-col gap-2 mt-5 w-full'>  
            <div className='flex justify-between w-full'>
                <label htmlFor={label} className='font-medium text-gray-700'>{label}</label>
                {errorMessage && <p className='text-red-500'>{errorMessage}</p>}
            </div>              
           
            <input type={type ?? 'text'} id={label} className={className ?? ('block max-w-full w-full px-3 py-2 rounded-md border-[0.5px] border-gray-300 outline-2 ' + (errorMessage ? 'outline-red-500 text-red-700' : 'focus-visible:outline-blue-500 outline-transparent bg-gray-100'))} placeholder={placeholder} onChange={onChange}  />
        </div>
     );
}

export default TextField