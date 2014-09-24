#Presenter

The base Presenter class included in the utilities can present arrays and objects. It acts as a proxy to the underlying data, allowing you to use properties, methods, array elements and any function of your data as normal while extending overriding the way the data is presented.

##Presenting a Simple Array

As an example, we'll start with a User represented as an array.

    $user = array(
        'firstname' => 'James',
        'lastname' => 'Bond',
        'email' => 'doubleohseven@headquarters.com',
    );

This data is representative of an entity, or a single row / document in a data store. It contains the elements just as they are retrieved from the database. We may wish to extend this entity with a "fullname" property that is derived from the first and last. Rather than modifying the underlying entity, we can calculate this property through a Presenter and make the process transparent to any view or other object consuming the Presenter.

####User Array Presenter

    class UserPresenter extends Presenter {
    
        public function getFullname()
        {
            return $this->firstname . ' ' . $this->lastname;
        }
    
    }

####Using The Presenter

    $user = array(
        'firstname' => 'James',
        'lastname' => 'Bond',
        'email' => 'doubleohseven@headquarters.com',
    );
    
    $presenter = new UserPresenter($user);
    echo $presenter->fullname; // 'James Bond';
    echo $presenter->lastname; // 'Bond';

####Obscuring a Property

For security, let's say that we don't ever want Bond's email address displayed in plain text, anywhere. We override what gets returned by the ->email property like so.

    class UserPresenter extends codenamegary\L4Utils\Presenter {
    
        public function getEmail()
        {
            // Get the original email address or return some placeholder
            if(!$email = $this->getRaw('email', false))
                return '***@***.com';
            
            // Explode the email address into a user and domain variable
            list($user, $domain) = explode('@', $email);
            
            // Explode the domain into a TLD and the rest of it
            $domainParts = explode('.', $domain);
            $tld = end($domainParts);
            // Pop the TLD part off the array
            array_pop($domainParts);
            // Make a string out of the remaining parts
            $domain = implode('.', $domainParts);
            
            // Make the user and domain contain just the first character plus obscured text (asterisks)
            $user = substr($user, 0, 1) . str_pad('', strlen($user)-1, '*');
            $domain = substr($domain, 0, 1) . str_pad('', strlen($domain)-1, '*');
            return $user . '@' . $domain . '.' . $tld;
        }
    
    }
    
    $user = array(
        'firstname' => 'James',
        'lastname' => 'Bond',
        'email' => 'doubleohseven@headquarters.com',
    );
    
    $presenter = new UserPresenter($user);
    echo $presenter->email; // 'd************@h***********.com'
