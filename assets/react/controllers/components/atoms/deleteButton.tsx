type Props = {
    onClick: (e: React.MouseEvent<HTMLButtonElement>) => void;
};

export default function DeleteButton({ onClick }: Props) {
    return (
        <span
            onClick={onClick}
            className="text-blue-600 hover:text-blue-800 transition-colors"
            title="Supprimer"
        >
            🗑️
        </span>
    );
}