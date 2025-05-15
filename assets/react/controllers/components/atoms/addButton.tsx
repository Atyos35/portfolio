type Props = {
    onClick: (e: React.MouseEvent<HTMLButtonElement>) => void;
};

export default function AddButton({ onClick }: Props) {
    return (
        <span
            onClick={onClick}
            className="text-green-600 hover:text-green-800 transition-colors"
            title="Ajouter"
        >
            ➕
        </span>
    );
}