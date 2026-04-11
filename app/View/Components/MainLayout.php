namespace App\View\Components\Layouts;

use Illuminate\View\Component;

class MainLayout extends Component
{
    public $title;
    public $breadcrumbs;

    public function __construct($title = null, $breadcrumbs = [])
    {
        $this->title = $title;
        $this->breadcrumbs = $breadcrumbs;
    }

    public function render()
    {
        return view('layouts.main');
    }
}
