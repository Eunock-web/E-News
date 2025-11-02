import { zodResolver } from '@hookform/resolvers/zod'
import React from 'react'
import { useForm } from 'react-hook-form'
import { RegisterSchema, type RegisterInputs } from '../../types'

const useFormRegister = () => {
  const form = useForm<RegisterInputs>({
    resolver: zodResolver(RegisterSchema)
  })

  const onSubmit = (data:RegisterInputs) => {
    console.log(data)
  }

  return {
    control: form.control,
    states: form.formState,
    onSubmit: form.handleSubmit(onSubmit, (data) => console.log(data))
  }
}

export default useFormRegister