import { zodResolver } from "@hookform/resolvers/zod";
import { RetrieveCodeSchema, type RetrieveCodeInputs } from "../../types"
import { useForm } from "react-hook-form"

const useFormResetPass = () => {
    const form = useForm<RetrieveCodeInputs>({
        resolver: zodResolver(RetrieveCodeSchema),
        mode:'onChange'
    });

    const onSubmit = (data:RetrieveCodeInputs) => {
       console.log(data)
    }

  return {
    control: form.control,
    onSubmit: form.handleSubmit(onSubmit, (data)=>console.log(data)), 
    states: form.formState 
  }
}

export default useFormResetPass