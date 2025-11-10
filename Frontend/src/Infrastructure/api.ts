
const BASEURL = 'http://127.0.0.1:8000/'; 

interface option  {
    'Content-Type': 'application/json',
    'Authorization': string,
    data?: object,
    method: string
};

export async function apiFetch(url:string, method = 'GET', data?:object ): Promise<any>{
    const options: option = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer',
        data: {},
        method: method
    }
    if(data) {
        method = 'POST'
        options.data = data
    }else {
        delete options.data
    }
    
    try {
        await fetch(BASEURL + url, options).then(response =>{ 
            if(response.ok){
                return response.json();
            }
            throw new Error(`Status: ${response.status}, No response. Error raised`)             
        }
        ).catch(err => console.error(err))
    }catch(error){
        return error;        
    }
}
    