<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Models\User;

class AuthRetrieveRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requiredRule = [
            'userData.id' => 'required|numeric',
            'userData.nickname' => 'required',
            'userData.profile' => 'string',
            'userData.job' => 'string',
            'userData.country' => 'required|string',
            'userData.city' => 'string',
            'userData.email' => 'email',
            'userData.mobile' => 'string',
            'userData.fax' => 'string',
            'userData.website' => 'string',
            'userData.position' => 'string',
            'userData.description' => 'string',
            'publicOption.mobile' => 'required|in:Public,Private',
            'publicOption.fax' => 'required|in:Public,Private',
            'publicOption.website' => 'required|in:Public,Private',
            'publicOption.email' => 'required|in:Public,Private',
        ];
        $historyObj = $this->exists('history') ? $this->request->get('history') : [] ;
        $languageObj = $this->exists('language') ? $this->request->get('language') : [] ;

        foreach($historyObj as $key => $value){
            $requiredRule['history.'.$key.'.content'] = 'required';
            $requiredRule['history.'.$key.'.date'] = 'required|date';
            $requiredRule['history.'.$key.'.category'] = 'required|in:Work Experience,Education,Awards';
        }
        foreach($languageObj as $key => $value){
            $requiredRule['language.'.$key.'.name'] = 'required';
            $requiredRule['language.'.$key.'.level'] = 'required|in:Beginner,Advanced,Fluent';
        }

        return $requiredRule;
    }
}
