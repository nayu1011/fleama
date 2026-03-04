<?php

namespace App\Mail;

use App\Models\Trade;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TradeCompletedReviewedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Trade $trade;
    public int $rating;
    public string $tradeUrl;

    public function __construct(Trade $trade, int $rating, string $tradeUrl)
    {
        $this->trade = $trade;
        $this->rating = $rating;
        $this->tradeUrl = $tradeUrl;
    }

    public function build()
    {
        return $this
            ->subject('「' . $this->trade->item->name . '」の取引が完了し評価されました（coachtechフリマ）')
            ->html(view('emails.trade_completed_reviewed', [
                'trade' => $this->trade,
                'rating' => $this->rating,
                'tradeUrl' => $this->tradeUrl,
            ])->render());
    }
}
