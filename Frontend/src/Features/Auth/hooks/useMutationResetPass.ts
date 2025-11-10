import { useMutation } from '@tanstack/react-query'
import React from 'react'

const useMutationResetPass = () => {
    const mutation = useMutation({
        mutationKey:['login'],
        mutationFn:()=>{},
        onSuccess:()=>{
            if(mutation.data)
        }
    })
  return 
}

export default useMutationResetPass