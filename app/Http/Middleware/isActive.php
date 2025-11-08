<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

//         if (auth()->user()->role_id == 7) {

//             //check if company document is uploaded
//             if (Company::where ('user_id',auth()->user()->id)->value('document_status') == 2){

// //                return redirect('documents/create?id=1');
//                 return redirect()->route('documents.create',['id'=>2]);
//             }

//         }

        return $next($request);
    }
}
