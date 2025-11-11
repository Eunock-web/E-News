import { zodResolver } from "@hookform/resolvers/zod";
import { ResetPassSchema, type ResetPassInputs } from "../../../types"
import { useForm } from "react-hook-form"
import useMutationResetPass from "../Mutation/useMutationResetPass";
import { useNavigate } from "react-router";

const useFormResetPass = () => {
    const form = useForm<ResetPassInputs>({
        resolver: zodResolver(ResetPassSchema),
        mode:'onChange'
    });

    const mutation = useMutationResetPass();
    const navigate = useNavigate();
    
    const onSubmit = async (data:ResetPassInputs) => {
       await mutation.mutateAsync(data);
       if(mutation.data?.success)
          navigate('/login')
    }

  return {
    control: form.control,
    onSubmit: form.handleSubmit(onSubmit, (data)=>console.log(data)), 
    states: form.formState 
  }
}

export default useFormResetPass