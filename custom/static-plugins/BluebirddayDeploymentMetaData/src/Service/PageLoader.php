<?php declare(strict_types=1);

namespace Bluebirdday\DeploymentMetaData\Service;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Shopware\Storefront\Page\Page;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class PageLoader
{
    private GenericPageLoaderInterface $genericPageLoader;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(GenericPageLoaderInterface $genericPageLoader, EventDispatcherInterface $eventDispatcher)
    {
        $this->genericPageLoader = $genericPageLoader;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function load(Request $request, SalesChannelContext $context): Page
    {
        $page = $this->genericPageLoader->load($request, $context);

        // Do additional stuff, e.g. load more data from repositories and add it to page
        // $page->setHeader()...

        // Maybe dispatch event. Just as an example.
        $this->eventDispatcher->dispatch(
            new Event($page, $context, $request)
        );

        return $page;
    }
}
